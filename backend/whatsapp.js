// ------------------------------
// Imports and dummy implementations
// ------------------------------

import { rmSync, readdir } from 'fs';
import { join, dirname } from 'path';
import pino from 'pino';
import makeWASocket, {
    useMultiFileAuthState,
    makeInMemoryStore,
    Browsers,
    DisconnectReason,
    delay,
} from '@whiskeysockets/baileys';
import { toDataURL } from 'qrcode';
import axios from 'axios';

// For ES Modules, define __dirname
import { fileURLToPath } from 'url';
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Dummy response function (replace with your actual implementation)
const response = (res, status, success, message, data = {}) => {
    if (res) {
        res.status(status).json({ success, message, data });
    } else {
        console.log({ status, success, message, data });
    }
};

// Dummy setDeviceStatus function (replace with your actual implementation)
const setDeviceStatus = (sessionId, status) => {
    console.log(`Device status for session ${sessionId} set to ${status}`);
};

// Dummy sentWebHook function (replace with your actual implementation)
const sentWebHook = (sessionId, data) => {
    console.log(`Webhook sent for session ${sessionId}:`, data);
};

// ------------------------------
// Session and Connection Management
// ------------------------------

// Map to store active sessions and reconnection attempts
const sessions = new Map();
const retries = new Map();

/**
 * Return the path for session files.
 * @param {string} sessionId
 * @returns {string}
 */
const sessionsDir = (sessionId = '') => join(__dirname, 'sessions', sessionId ? sessionId : '');

/**
 * Check if a session exists.
 * @param {string} sessionId
 */
const isSessionExists = (sessionId) => sessions.has(sessionId);

/**
 * Check if a session should reconnect based on the number of attempts.
 * Uses process.env.MAX_RETRIES (defaults to 1 if not set).
 * @param {string} sessionId
 */
const shouldReconnect = (sessionId) => {
    let maxRetries = parseInt(process.env.MAX_RETRIES ?? 0);
    let attempts = retries.get(sessionId) ?? 0;

    maxRetries = maxRetries < 1 ? 1 : maxRetries;

    if (attempts < maxRetries) {
        attempts++;
        console.log('Reconnecting...', { attempts, sessionId });
        retries.set(sessionId, attempts);
        return true;
    }

    return false;
};

/**
 * Create and manage a WhatsApp session.
 * @param {string} sessionId
 * @param {boolean} isLegacy
 * @param {object|null} res (optional HTTP response object)
 */
const createSession = async (sessionId, isLegacy = false, res = null) => {
    // Define the session file name based on legacy or multi-device
    const sessionFile = (isLegacy ? 'legacy_' : 'md_') + sessionId + (isLegacy ? '.json' : '');
    const logger = pino({ level: 'warn' });
    const store = makeInMemoryStore({ logger });
    let state, saveState;

    if (isLegacy) {
        // Legacy session logic (if any) goes here.
    } else {
        // Use multi-file auth state for multi-device sessions.
        ({ state, saveCreds: saveState } = await useMultiFileAuthState(sessionsDir(sessionFile)));
    }

    /**
     * WhatsApp connection configuration.
     */
    const waConfig = {
        auth: state,
        version: [2, 3000, 1015901307],
        printQRInTerminal: false,
        logger,
        browser: Browsers.ubuntu('Chrome'),
        patchMessageBeforeSending: (message) => {
            // Some messages (like buttons or list messages) require a specific format.
            const requiresPatch = !!(message.buttonsMessage || message.listMessage);
            if (requiresPatch) {
                message = {
                    viewOnceMessage: {
                        message: {
                            messageContextInfo: {
                                deviceListMetadataVersion: 2,
                                deviceListMetadata: {},
                            },
                            ...message,
                        },
                    },
                };
            }
            return message;
        },
    };

    // Initialize the WhatsApp socket connection.
    const wa = makeWASocket.default(waConfig);

    if (!isLegacy) {
        // Read chat store data from file if available, then bind event listeners.
        store.readFromFile(sessionsDir(`${sessionId}_store.json`));
        store.bind(wa.ev);
    }

    // Save the session to the sessions map.
    sessions.set(sessionId, { ...wa, store, isLegacy });

    // Update credentials on changes.
    wa.ev.on('creds.update', saveState);

    // For legacy sessions, insert chats if not already present.
    wa.ev.on('chats.set', ({ chats }) => {
        if (isLegacy) {
            store.chats.insertIfAbsent(...chats);
        }
    });

    // Listen for incoming messages.
    wa.ev.on('messages.upsert', async (messages) => {
        try {
            const message = messages.messages[0];

            // Process messages not sent by yourself and that are notifications.
            if (!message.key.fromMe && messages.type === 'notify') {
                const received_data = [];
                const parseId = message.key.remoteJid.split("@");
                const splitId = parseId[1] ?? null;
                const isGroup = splitId === 's.whatsapp.net' ? false : true;

                // If not a group message, prepare data and send a webhook.
                if (message && !isGroup) {
                    received_data['remote_id'] = message.key.remoteJid;
                    received_data['sessionId'] = sessionId;
                    received_data['message_id'] = message.key.id;
                    received_data['message'] = message.message;
                    sentWebHook(sessionId, received_data);
                }
            }
        } catch (error) {
            // Log error if needed.
            console.error('Error in messages.upsert:', error);
        }
    });

    // Listen for connection updates.
    wa.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect } = update;
        const statusCode = lastDisconnect?.error?.output?.statusCode;

        // If connection is open, reset reconnection attempts.
        if (connection === 'open') {
            retries.delete(sessionId);
        }

        // If the connection is closed, decide to reconnect or delete the session.
        if (connection === 'close') {
            if (statusCode === DisconnectReason.loggedOut || !shouldReconnect(sessionId)) {
                if (res && !res.headersSent) {
                    response(res, 500, false, 'Unable to create session.');
                }
                return deleteSession(sessionId, isLegacy);
            }

            // Reconnect after a delay (or immediately if a restart is required).
            setTimeout(
                () => {
                    createSession(sessionId, isLegacy, res);
                },
                statusCode === DisconnectReason.restartRequired ? 0 : parseInt(process.env.RECONNECT_INTERVAL ?? 0)
            );
        }

        // When a QR code is received, send it in the HTTP response (if provided) and then logout.
        if (update.qr) {
            if (res && !res.headersSent) {
                try {
                    const qr = await toDataURL(update.qr);
                    response(res, 200, true, 'QR code received, please scan the QR code.', { qr });
                    return;
                } catch {
                    response(res, 500, false, 'Unable to create QR code.');
                }
            }

            try {
                await wa.logout();
            } catch {
                // Handle logout error silently.
            } finally {
                deleteSession(sessionId, isLegacy);
            }
        }
    });
};

/**
 * Retrieve an active session.
 * @param {string} sessionId
 * @returns {import('@adiwajshing/baileys').AnyWASocket|null}
 */
const getSession = (sessionId) => sessions.get(sessionId) ?? null;

/**
 * Delete a session and remove its stored files.
 * @param {string} sessionId
 * @param {boolean} isLegacy
 */
const deleteSession = (sessionId, isLegacy = false) => {
    const sessionFile = (isLegacy ? 'legacy_' : 'md_') + sessionId + (isLegacy ? '.json' : '');
    const storeFile = `${sessionId}_store.json`;
    const rmOptions = { force: true, recursive: true };

    rmSync(sessionsDir(sessionFile), rmOptions);
    rmSync(sessionsDir(storeFile), rmOptions);

    sessions.delete(sessionId);
    retries.delete(sessionId);

    // Update device status to offline.
    setDeviceStatus(sessionId, 0);
};

/**
 * Get the list of chats from a session.
 * @param {string} sessionId
 * @param {boolean} isGroup
 */
const getChatList = (sessionId, isGroup = false) => {
    const filter = isGroup ? '@g.us' : '@s.whatsapp.net';
    return getSession(sessionId).store.chats.filter((chat) => chat.id.endsWith(filter));
};

/**
 * Check if a contact or group exists.
 * @param {import('@adiwajshing/baileys').AnyWASocket} session
 * @param {string} jid
 * @param {boolean} isGroup
 */
const isExists = async (session, jid, isGroup = false) => {
    try {
        let result;
        if (isGroup) {
            result = await session.groupMetadata(jid);
            return Boolean(result.id);
        }
        if (session.isLegacy) {
            result = await session.onWhatsApp(jid);
        } else {
            [result] = await session.onWhatsApp(jid);
        }
        return result.exists;
    } catch {
        return false;
    }
};

/**
 * Send a message after an optional delay.
 * @param {import('@adiwajshing/baileys').AnyWASocket} session
 * @param {string} receiver
 * @param {object} message
 * @param {number} delayMs
 */
const sendMessage = async (session, receiver, message, delayMs = 1000) => {
    try {
      console.log("sendMessage: receiver ",JSON.stringify(receiver))
      console.log("sendMessage: message ",JSON.stringify(message))
      console.log("sendMessage: session ",JSON.stringify(session!=null))
        await delay(parseInt(delayMs));
        return session.sendMessage(receiver, message);
    } catch {
        return Promise.reject(null);
    }
};

/**
 * Format a phone number to the WhatsApp ID.
 * @param {string} phone
 */
const formatPhone = (phone) => {
    if (phone.endsWith('@s.whatsapp.net')) return phone;
    let formatted = phone.replace(/\D/g, '');
    return (formatted += '@s.whatsapp.net');
};

/**
 * Format a group ID for WhatsApp.
 * @param {string} group
 */
const formatGroup = (group) => {
    if (group.endsWith('@g.us')) return group;
    let formatted = group.replace(/[^\d-]/g, '');
    return (formatted += '@g.us');
};

/**
 * Cleanup function to save session stores before exit.
 */
const cleanup = () => {
    console.log('Running cleanup before exit.');
    sessions.forEach((session, sessionId) => {
        if (!session.isLegacy) {
            session.store.writeToFile(sessionsDir(`${sessionId}_store.json`));
        }
    });
};

/**
 * Initialize sessions by reading saved session files.
 */
const init = () => {
    readdir(sessionsDir(), (err, files) => {
        if (err) {
            throw err;
        }
        for (const file of files) {
            // Ignore files that are not session files.
            if ((!file.startsWith('md_') && !file.startsWith('legacy_')) || file.endsWith('_store')) {
                continue;
            }
            const filename = file.replace('.json', '');
            const isLegacy = filename.split('_', 1)[0] !== 'md';
            const sessionId = filename.substring(isLegacy ? 7 : 3);
            createSession(sessionId, isLegacy);
        }
    });
};

// ------------------------------
// Exporting functions for external use
// ------------------------------

export {
    isSessionExists,
    createSession,
    getSession,
    deleteSession,
    getChatList,
    isExists,
    sendMessage,
    formatPhone,
    formatGroup,
    cleanup,
    init,
};
