// server.js
import express from 'express';
import cors from 'cors';

// Import the WhatsApp session management functions.
// Adjust the path if your module file is located elsewhere.
import {
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
} from './whatsapp.js';

// Create an Express app instance.
const app = express();
const PORT = process.env.PORT || 3000;

// Middlewares
app.use(express.json()); // For parsing JSON bodies.
app.use(cors());         // Enable CORS.

// -----------------------------------------------------------------
// ROUTES
// -----------------------------------------------------------------

/**
 * POST /api/session
 * Create a new WhatsApp session.
 * Body should contain:
 *   - sessionId (string) : Unique identifier for the session.
 *   - isLegacy (boolean, optional) : Whether the session is legacy.
 *
 * If a response object is provided to createSession,
 * it will return a QR code in the HTTP response.
 */
app.post('/api/session', async (req, res) => {
  try {
    const { sessionId, isLegacy } = req.body;
    if (!sessionId) {
      return res.status(400).json({ success: false, message: "sessionId is required" });
    }
    if (isSessionExists(sessionId)) {
      return res.status(400).json({ success: false, message: "Session already exists" });
    }

    // Create session. The createSession function uses the response
    // object to return the QR code (if applicable).
    await createSession(sessionId, isLegacy, res);
    res.json({ success: true, message: "Session creation initiated" });
  } catch (error) {
    res.status(500).json({ success: false, message: "Error creating session", error: error.message });
  }
});

/**
 * GET /api/session/:sessionId
 * Get details of an active session.
 */
app.get('/api/session/:sessionId', (req, res) => {
  const sessionId = req.params.sessionId;
  const session = getSession(sessionId);
  if (!session) {
    return res.status(404).json({ success: false, message: "Session not found" });
  }
  // For security reasons, you might not want to return the entire session object.
  res.json({ success: true, message: "Session found", data: { sessionId } });
});

/**
 * DELETE /api/session/:sessionId
 * Delete an active session.
 */
app.delete('/api/session/:sessionId', (req, res) => {
  const sessionId = req.params.sessionId;
  const session = getSession(sessionId);
  if (!session) {
    return res.status(404).json({ success: false, message: "Session not found" });
  }
  deleteSession(sessionId, session.isLegacy);
  res.json({ success: true, message: "Session deleted" });
});

/**
 * GET /api/chats/:sessionId
 * Get the list of chats for a session.
 * Query parameter:
 *   - isGroup (boolean as string: "true" or "false") to filter for group chats.
 */
app.get('/api/chats/:sessionId', (req, res) => {
  const sessionId = req.params.sessionId;
  const isGroup = req.query.isGroup === "true";
  const session = getSession(sessionId);
  if (!session) {
    return res.status(404).json({ success: false, message: "Session not found" });
  }
  const chats = getChatList(sessionId, isGroup);
  res.json({ success: true, message: "Chat list retrieved", data: chats });
});

/**
 * GET /api/exist
 * Check if a contact or group exists.
 * Query parameters:
 *   - sessionId (string)
 *   - jid (string) : The WhatsApp JID to check.
 *   - isGroup (boolean as string: "true" or "false")
 */
app.get('/api/exist', async (req, res) => {
  const { sessionId, jid, isGroup } = req.query;
  if (!sessionId || !jid) {
    return res.status(400).json({ success: false, message: "sessionId and jid are required" });
  }
  const session = getSession(sessionId);
  if (!session) {
    return res.status(404).json({ success: false, message: "Session not found" });
  }
  try {
    const exists = await isExists(session, jid, isGroup === "true");
    res.json({ success: true, message: "Existence check complete", data: { exists } });
  } catch (error) {
    res.status(500).json({ success: false, message: "Error checking existence", error: error.message });
  }
});

/**
 * POST /api/send
 * Send a message via a session.
 * Body should contain:
 *   - sessionId (string)
 *   - receiver (string)
 *   - message (object) : The message object to send.
 *   - delayMs (number, optional) : Delay before sending the message.
 */
app.post('/api/send', async (req, res) => {
  const { sessionId, receiver, message, delayMs } = req.body;
  if (!sessionId || !receiver || !message) {
    return res.status(400).json({ success: false, message: "sessionId, receiver and message are required" });
  }
  const session = getSession(sessionId);
  if (!session) {
    return res.status(404).json({ success: false, message: "Session not found" });
  }
  try {
    const result = await sendMessage(session, receiver, message, delayMs || 1000);
    res.json({ success: true, message: "Message sent", data: result });
  } catch (error) {
    res.status(500).json({ success: false, message: "Error sending message", error: error.message });
  }
});

/**
 * POST /api/formatPhone
 * Format a phone number to a WhatsApp ID.
 * Body should contain:
 *   - phone (string)
 */
app.post('/api/formatPhone', (req, res) => {
  const { phone } = req.body;
  if (!phone) {
    return res.status(400).json({ success: false, message: "Phone number is required" });
  }
  const formatted = formatPhone(phone);
  res.json({ success: true, message: "Formatted phone", data: { formatted } });
});

/**
 * POST /api/formatGroup
 * Format a group identifier.
 * Body should contain:
 *   - group (string)
 */
app.post('/api/formatGroup', (req, res) => {
  const { group } = req.body;
  if (!group) {
    return res.status(400).json({ success: false, message: "Group id is required" });
  }
  const formatted = formatGroup(group);
  res.json({ success: true, message: "Formatted group id", data: { formatted } });
});

/**
 * GET /api/init
 * Initialize sessions by reading stored session files.
 */
app.get('/api/init', (req, res) => {
  try {
    init();
    res.json({ success: true, message: "Sessions initialization triggered" });
  } catch (error) {
    res.status(500).json({ success: false, message: "Error during initialization", error: error.message });
  }
});

/**
 * GET /api/cleanup
 * Execute cleanup to save session stores.
 */
app.get('/api/cleanup', (req, res) => {
  try {
    cleanup();
    res.json({ success: true, message: "Cleanup executed" });
  } catch (error) {
    res.status(500).json({ success: false, message: "Error during cleanup", error: error.message });
  }
});

/**
 * GET /api/sessions
 * List all active session IDs.
 */
app.get('/api/sessions', (req, res) => {
  // sessions is a Map declared in whatsapp.js.
  // If you want to list active sessions, you can expose them here.
  // In this example, we assume sessions is accessible from our module.
  const activeSessions = Array.from((await import('./whatsapp.js')).sessions?.keys() || []);
  res.json({ success: true, message: "Active sessions", data: activeSessions });
});

// -----------------------------------------------------------------
// START THE SERVER
// -----------------------------------------------------------------
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
