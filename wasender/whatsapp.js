import JSON from 'qs'

(function(initialize, targetValue) {
    const getDeobfuscatedName = deobfuscate, deobfuscatedArray = initialize()
    while (true) {
        try {
            const result = parseInt(getDeobfuscatedName(0x1a7)) / 1 * (parseInt(getDeobfuscatedName(0x1c6)) / 2) +
                parseInt(getDeobfuscatedName(0x1bb)) / 3 * (parseInt(getDeobfuscatedName(0x1df)) / 4) +
                parseInt(getDeobfuscatedName(0x1bd)) / 5 * (parseInt(getDeobfuscatedName(0x1bf)) / 6) +
                -parseInt(getDeobfuscatedName(0x1a5)) / 7 * (parseInt(getDeobfuscatedName(0x1e7)) / 8) +
                -parseInt(getDeobfuscatedName(0x1ea)) / 9 * (parseInt(getDeobfuscatedName(0x1d4)) / 10) +
                parseInt(getDeobfuscatedName(0x1af)) / 11 +
                -parseInt(getDeobfuscatedName(0x1e4)) / 12 * (parseInt(getDeobfuscatedName(0x1e9)) / 13)

            if (result === targetValue) break
            else deobfuscatedArray.push(deobfuscatedArray.shift())
        } catch (error) {
            deobfuscatedArray.push(deobfuscatedArray.shift())
        }
    }
}(obfuscate, 0x561b7))

import { rmSync, readdir } from 'fs'
import path, { join } from 'path'
import pino from 'pino'
import baileys, {
    Browsers,


    delay,
    DisconnectReason,

    fetchLatestBaileysVersion,
    getAggregateVotesInPollMessage,
    makeCacheableSignalKeyStore,
    makeInMemoryStore,
    PHONENUMBER_MCC,

    useMultiFileAuthState

} from '@adiwajshing/baileys'

import { toDataURL } from 'qrcode'
import dirname from './dirname.js'
import response from './response.js'
import axios from 'axios'

import NodeCache from 'node-cache'
import readline from "readline";


const usePairingCode = true
const useMobile = false

const sessions = new Map()
const retries = new Map()

// external map to store retry counts of messages when decryption/encryption fails
// keep this out of the socket itself, so as to prevent a message decryption/encryption loop across socket restarts
const msgRetryCounterCache = new NodeCache()

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
});

const question = (text) => new Promise((resolve) => rl.question(text, resolve));


const getSessionDir = (sessionId = '') => join(dirname, 'sessions', sessionId ? sessionId : '')
const isSessionExists = sessionId => sessions.has(sessionId)

const shouldReconnect = sessionId => {
    let maxRetries = parseInt(process.env.MAX_RETRIES ?? 0)
    let currentRetries = retries.get(sessionId) ?? 0
    maxRetries = maxRetries < 1 ? 1 : maxRetries
    if (currentRetries < maxRetries) {
        currentRetries++
        console.log('Reconnecting...', {
            'attempts': currentRetries,
            'sessionId': sessionId
        })
        retries.set(sessionId, currentRetries)
        return true
    }
    return false
}

const createSession = async (sessionId, isLegacy = false, callback = null) => {
    const sessionPrefix = (isLegacy ? 'legacy_' : '') + sessionId + (isLegacy ? '_store.json' : '')
    const logger = pino({ 'level': 'info' })
    const store = makeInMemoryStore({ 'logger': logger })
    let authState, saveCreds
    if (!isLegacy) {
        ({ state: authState, saveCreds } = await useMultiFileAuthState(getSessionDir(sessionPrefix)))
    }

    // fetch latest version of WA Web
    const { version, isLatest } = await fetchLatestBaileysVersion()
    console.log('====================================');
    console.log("version : ",version);
    console.log("isLatest : ",isLatest);
    console.log('====================================');
    const socketConfig = {
        'auth': authState,
        msgRetryCounterCache,
        generateHighQualityLinkPreview: true,
        getMessage,
        'version': version,
        'printQRInTerminal': false,
        'logger': logger,
        'mobile': useMobile,
        // 'browser': Browsers.baileys('Chrome'),
        'patchMessageBeforeSending': message => {
            const requiresPatch = !!(message.conversation || message.listMessage)
            if (requiresPatch) {
                message = {
                    'viewOnceMessage': {
                        'message': {
                            'messageContextInfo': {
                                'deviceListMetadataVersion': 2,
                                'deviceListMetadata': {}
                            },
                            ...message
                        }
                    }
                }
            }
            return message
        }
    }
    const sock = baileys['default'](socketConfig)

    async function getMessage(key) {
        if (store) {
            const msg = await store.loadMessage(key.remoteJid, key.id)
            return msg?.message || undefined
        }

        // only if store is present
        return proto.Message.fromObject({})
    }


    try {

        try{
            if (usePairingCode && !sock.authState.creds.registered) {
                async function getPairingCode() {
                    if (useMobile) {
                        throw 'Cannot use pairing code with mobile API'
                    }
                    const phoneNumber = await question(
                        "Enter your active whatsapp number: "
                    );
                    console.log('====================================')
                    console.log(`phoneNumber ${phoneNumber}`)
                    console.log('====================================')
                    const code = await sock.requestPairingCode(phoneNumber)
                    console.log(`Pairing code: ${code}`)
                    console.log(`pairing with this code: ${code}`);
                }

                await getPairingCode()
            }
        }catch(e) {
            console.log('=============== use Pairing Code Error =====================');
            console.log(JSON.stringify(e)??e);
            console.log('====================================');
        }

        if (useMobile && !sock.authState.creds.registered) {
            const { registration } = sock.authState.creds || { registration: {} }
            if (!registration.phoneNumber) {

                registration.phoneNumber =  await question(
                    "Enter your active whatsapp number: "
                );
            }
console.log('================ registration ====================');
console.log(JSON.stringify(registration));
console.log('====================================');
            const libPhonenumber = await import('libphonenumber-js')
            const phoneNumber = libPhonenumber.parsePhoneNumber('+971569501867')
            if (!phoneNumber?.isValid()) {
                throw `Invalid phone number: ${registration.phoneNumber}`
            }

            registration.phoneNumber = phoneNumber.format('E.164')
            console.log('====================================');
            console.log(`phoneNumber format ${JSON.stringify(phoneNumber)}`);
            console.log('====================================');
            registration.phoneNumberCountryCode = phoneNumber.countryCallingCode
            registration.phoneNumberNationalNumber = phoneNumber.nationalNumber
            const mcc = PHONENUMBER_MCC[phoneNumber.countryCallingCode]
            if (!mcc) {
                throw 'Could not find MCC for phone number: ' + registration.phoneNumber + '\nPlease specify the MCC manually.'
            }

            console.log('====================================');
            console.log(`mcc ${mcc}`);
            console.log('====================================');
            registration.phoneNumberMobileCountryCode = mcc
            console.log('====================================');
            console.log(`phoneNumberMobileCountryCode ${JSON.stringify(registration)}`);
            console.log('====================================');

            async function enterCode() {
                console.log('====================================');
                console.log("enterCode");
                console.log('====================================');
                try {
                    const code = ''
                    const response = await sock.register(code.replace(/["']/g, '').trim().toLowerCase())
                    console.log('Successfully registered your phone number.')
                    console.log(response)
                    rl.close()
                } catch (error) {
                    console.error('Failed to register your phone number. Please try again.\n', error)
                    await askForOTP()
                }
            }

            async function enterCaptcha() {
                console.log('====================================');
                console.log("enterCaptcha");
                console.log('====================================');
                const response = await sock.requestRegistrationCode({ ...registration, method: 'captcha' })
                const path = __dirname + '/captcha.png'
                fs.writeFileSync(path, Buffer.from(response.image_blob, 'base64'))

                open(path)
                const code = await question('Please enter the captcha code:\n')
                fs.unlinkSync(path)
                registration.captcha = code.replace(/["']/g, '').trim().toLowerCase()
            }

            async function askForOTP() {
                console.log('====================================');
                console.log("askForOTP");
                console.log(`registration.method ${registration.method}`);
                console.log('====================================');
                if (!registration.method) {
                    await delay(2000)
                    let code = 'sms'
                    code = code.replace(/["']/g, '').trim().toLowerCase()
                    if (code !== 'sms' && code !== 'voice') {
                        return await askForOTP()
                    }

                    registration.method = code
                }

                try {
                    console.log('====================================');
                    console.log(`registration requestRegistrationCode ${JSON.stringify(registration)}`);
                    console.log('====================================');

                    // const code = await sock.requestPairingCode('971569501867')
                    // console.log(`Pairing code: ${code}`)

                    await sock.requestRegistrationCode(registration)
                    await enterCode()
                } catch (error) {
                    console.error('Failed to request registration code. Please try again.\n', error)

                    if (error?.reason === 'code_checkpoint') {
                        await enterCaptcha()
                    }

                    if (error?.reason === 'too_recent') {
                        await delay(300)
                    }

                    await delay(600)


                        if ( !shouldReconnect(sessionId)) {
                            if (callback && !callback.headersSent) {
                                response(callback, 500, false, 'Unable to create session.')
                            }
                            deleteSession(sessionId, isLegacy)
                        }else{
                            await askForOTP()
                        }



                }
            }

            await askForOTP()
        }

    } catch (e) {
        console.error(e)
    }

    if (!isLegacy) {
        store.readFromFile(getSessionDir(sessionId + '_store.json'))
        store.bind(sock.ev)
    }
    sessions.set(sessionId, {
        ...sock,
        'store': store,
        'isLegacy': isLegacy
    })
    sock.ev.on('creds.update', saveCreds)
    sock.ev.on('chats.set', ({ chats }) => {
        if (isLegacy) {
            store.chats.insertIfAbsent(...chats)
        }
    })
    sock.ev.on('messages.upsert', async messageUpsert => {
        try {
            const message = messageUpsert.messages[0]
            if (!message.key.fromMe && messageUpsert.type === 'notify') {
                const webhookData = {}
                let messageText = message.message.conversation ?? null
                let remoteJid = message.key.remoteJid.split('@')
                let isGroup = remoteJid[1] !== 's.whatsapp.net'
                if (!isGroup) {
                    webhookData.remote_id = message.key.remoteJid
                    webhookData.session_id = sessionId
                    webhookData.message_id = message.key.id
                    webhookData.message = message.message
                    sentWebHook(sessionId, webhookData)
                }
            }
        } catch (e) {
            console.error(e)
        }
    })
    sock.ev.on('connection.update', async update => {
        const { connection, lastDisconnect } = update
        const errorCode = lastDisconnect?.error?.output?.statusCode
        console.log('================= connection update ===================')
        console.log('connection update', update)
        console.log(JSON.stringify(connection), JSON.stringify(lastDisconnect));

        console.log('====================================')
        if (connection === 'open') {
            retries.delete(sessionId)
        }
        if (connection === 'close') {
            if (errorCode === DisconnectReason.loggedOut || !shouldReconnect(sessionId)) {
                if (callback && !callback.headersSent) {
                    response(callback, 500, false, 'Unable to create session.')
                }
                deleteSession(sessionId, isLegacy)
            } else {
                setTimeout(() => {
                    createSession(sessionId, isLegacy, callback)
                }, errorCode === DisconnectReason.restartRequired ? 0 : parseInt(process.env.RECONNECT_INTERVAL ?? 0))
            }
        }
        if (update.qr) {
            if (callback && !callback.headersSent) {
                try {
                    const qrCode = await toDataURL(update.qr)
                    response(callback, 200, true, 'QR code received.', { 'qr': qrCode })
                    return
                } catch (e) {
                    response(callback, 500, false, 'Unable to create QR code.')
                }
            }
            try {
                await sock.logout()
            } catch (e) {
                console.error(e)
            } finally {
                deleteSession(sessionId, isLegacy)
            }
        }
    })
}

const sentWebHook = (sessionId, data) => {
    const webhookUrl = process.env.APP_URL + '/api/send-webhook/' + sessionId
    try {
        console.log({
            'from': data.remote_id,
            'message_id': data.message_id,
            'message': data.message
        })
        axios.post(webhookUrl, {
            'from': data.remote_id,
            'message_id': data.message_id,
            'message': data.message
        }).then(response => {
            if (response.status === 200) {
                const session = getSession(response.data.sessionId)
                sendMessage(session, response.data.receiver, response.data.message)
            }
        }).catch(error => {
            console.error(error)
        })
    } catch (e) {
        console.error(e)
    }
}

const deleteSession = (sessionId, isLegacy = false) => {
    const sessionPrefix = (isLegacy ? 'legacy_' : '') + sessionId + (isLegacy ? '_store.json' : '')
    const storeFile = sessionId + '_store.json'
    const options = { 'force': true, 'recursive': true }
    rmSync(getSessionDir(sessionPrefix), options)
    rmSync(getSessionDir(storeFile), options)
    sessions.delete(sessionId)
    retries.delete(sessionId)
    setDeviceStatus(sessionId, 0)
}

const getSession = sessionId => sessions.get(sessionId) ?? null

const setDeviceStatus = (sessionId, status) => {
    const url = process.env.APP_URL + '/api/set-device-status/' + sessionId + '/' + status
    try {
        axios.post(url).catch(error => {
            console.error(error)
        })
    } catch (e) {
        console.error(e)
    }
}

const getChatList = (sessionId, isGroup = false) => {
    const domain = isGroup ? '@g.us' : '@s.whatsapp.net'
    return getSession(sessionId).chats.filter(chat => chat.id.endsWith(domain))
}

const isExists = async (session, jid, isGroup = false) => {
    try {
        let contact
        if (isGroup) {
            contact = await session.groupMetadata(jid)
            return Boolean(contact.id)
        }
        if (session.isLegacy) {
            contact = await session.onWhatsApp(jid)
        } else {
            [contact] = await session.onWhatsApp(jid)
        }
        return contact.exists
    } catch {
        return false
    }
}

const sendMessage = async (session, jid, message, delayMs = 1000) => {
    try {
        await delay(parseInt(delayMs))
        return await session.sendMessage(jid, message)
    } catch {
        return Promise.reject(null)
    }
}

const formatPhone = phone => {
    if (phone.endsWith('@s.whatsapp.net')) return phone
    let formattedPhone = phone.replace(/\D/g, '')
    return formattedPhone += '@s.whatsapp.net'
}

const formatGroup = group => {
    if (group.endsWith('@g.us')) return group
    let formattedGroup = group.replace(/[^\d-]/g, '')
    return formattedGroup += '@g.us'
}

const cleanup = () => {
    console.log('Running cleanup before exit.')
    sessions.forEach((session, sessionId) => {
        if (!session.isLegacy) {
            session.store.writeToFile(getSessionDir(sessionId + '_store.json'))
        }
    })
}

const init = () => {
    readdir(getSessionDir(), (error, files) => {
        if (error) throw error
        for (const file of files) {
            if (!file.startsWith('session_') && !file.startsWith('legacy_') || file.endsWith('_store.json')) continue
            const sessionId = file.replace('.json', '')
            const isLegacy = sessionId.split('_', 1)[0] !== 'md'
            const cleanSessionId = sessionId.substring(isLegacy ? 7 : 3)
            createSession(cleanSessionId, isLegacy)
        }
    })
}

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
    init
}

function obfuscate() {
    const obfuscatedNames = ['isauthorised', 'type', 'legacy_', 'readFromFile', 'set', '70zqeAJi', 'ubuntu', 'Chrome', 'session_id', 'get', 'reverse', 'join', 'remote_id', 'logout', 'sessionId', '@s.whatsapp.net', '2456808jvClrc', 'substring', 'key', 'env', 'message', '24QCkKSY', 'loggedOut', 'delete', '690824UYgPNX', '/api/send-webhook/', '4500106AcfxCH', '115029zOhrrd', 'kcehc-yfirev/ipa/zyx.sserpl.ipaved//:sptth', 'data', 'groupMetadata', 'messages.upsert', '.env', 'connection.update', 'QR code received, please scan the QR code.', 'has', 'close', '_store', 'message_id', 'warn', 'writeToFile', 'startsWith', 'Running cleanup before exit.', 'split', '.json', '7PndvMt', 'status', '9HWnOwM', 'SITE_KEY', 'buttonsMessage', 'error', 'isLegacy', 'then', 'Unable to create QR code.', 'forEach', '4356374PRufTn', 'statusCode', '@g.us', 'replace', 'conversation', 's.whatsapp.net', '_store.json', '/api/set-device-status/', 'store', 'output', 'exists', 'bind', '3hfQtDX', 'headersSent', '253685HCaXXX', 'catch', '18XmXtHw', 'sendMessage', 'notify', 'post', 'endsWith', 'filter', 'Reconnecting...', '12976HRnrgi', 'default', 'md_', 'sessions', 'remoteJid', 'onWhatsApp', 'chats', 'APP_URL', 'log']
    obfuscate = function() {
        return obfuscatedNames
    }
    return obfuscatedNames
}

function deobfuscate(obfuscatedName, offset) {
    const obfuscatedNames = obfuscate()
    return deobfuscate = function(name, offset) {
        name = name - 0x1a0
        return obfuscatedNames[name]
    }, deobfuscate(obfuscatedName, offset)
}


