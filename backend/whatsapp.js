import { downloadMediaMessage } from '@whiskeysockets/baileys'
import { writeFile } from 'fs/promises'
import  logger  from 'logger'
import fs from 'fs/promises';


(function (_0x57ca8e, _0x7d76df) {
    const _0x15f954 = _0x130c,
        _0xddce0f = _0x57ca8e();
    while (!![]) {
        try {
            const _0x31adff =
                (parseInt(_0x15f954(0x1a7)) / 0x1) *
                (parseInt(_0x15f954(0x1c6)) / 0x2) +
                (parseInt(_0x15f954(0x1bb)) / 0x3) *
                (parseInt(_0x15f954(0x1df)) / 0x4) +
                (parseInt(_0x15f954(0x1bd)) / 0x5) *
                (parseInt(_0x15f954(0x1bf)) / 0x6) +
                (-parseInt(_0x15f954(0x1a5)) / 0x7) *
                (parseInt(_0x15f954(0x1e7)) / 0x8) +
                (-parseInt(_0x15f954(0x1ea)) / 0x9) *
                (parseInt(_0x15f954(0x1d4)) / 0xa) +
                parseInt(_0x15f954(0x1af)) / 0xb +
                (-parseInt(_0x15f954(0x1e4)) / 0xc) *
                (parseInt(_0x15f954(0x1e9)) / 0xd);
            if (_0x31adff === _0x7d76df) break;
            else _0xddce0f["push"](_0xddce0f["shift"]());
        } catch (_0x2638a4) {
            _0xddce0f["push"](_0xddce0f["shift"]());
        }
    }
})(_0x1ebb, 0x561b7);
import { rmSync, readdir } from "fs";
function _0x130c(_0x3de393, _0x5ef6ad) {
    const _0x1ebbf4 = _0x1ebb();
    return (
        (_0x130c = function (_0x130c2f, _0x42bdcd) {
            _0x130c2f = _0x130c2f - 0x1a0;
            let _0x435796 = _0x1ebbf4[_0x130c2f];
            return _0x435796;
        }),
            _0x130c(_0x3de393, _0x5ef6ad)
    );
}
import _0x40b70e from "fs";
import { join } from "path";
import _0x5a133f from "pino";
import _0x3bd1a4, {
    useMultiFileAuthState,
    makeInMemoryStore,
    Browsers,
    DisconnectReason,
    delay,
} from "@whiskeysockets/baileys";
// "@adiwajshing/baileys" http://%22@adiwajshing/baileys%22:%20%22github:WhiskeySockets/Baileys%22,
import { toDataURL } from "qrcode";
import _0x4bf9b7 from "./dirname.js";
import _0x16d596 from "./response.js";
import _0x5e2a90 from "axios";
const sessions = new Map(),
    retries = new Map(),
    sessionsDir = (_0x2e7831 = "") => {
        const _0x410955 = _0x130c;
        return join(_0x4bf9b7, _0x410955(0x1c9), _0x2e7831 ? _0x2e7831 : "");
    },
    isSessionExists = (_0x427c5c) => {
        const _0x55226d = _0x130c;
        return sessions[_0x55226d(0x1f2)](_0x427c5c);
    },
    shouldReconnect = (_0x1e8b57) => {
        const _0x42e6e6 = _0x130c;
        let _0x9f336f = parseInt(process["env"]["MAX_RETRIES"] ?? 0x0),
            _0xf60215 = retries[_0x42e6e6(0x1d8)](_0x1e8b57) ?? 0x0;
        _0x9f336f = _0x9f336f < 0x1 ? 0x1 : _0x9f336f;
        if (_0xf60215 < _0x9f336f)
            return (
                ++_0xf60215,
                    console["log"](_0x42e6e6(0x1c5), {
                        attempts: _0xf60215,
                        sessionId: _0x1e8b57,
                    }),
                    retries[_0x42e6e6(0x1d3)](_0x1e8b57, _0xf60215),
                    !![]
            );
        return ![];
    },
    createSession = async (_0x504074, _0x54de04 = ![], _0x1ab13f = null) => {
        const _0x2d363b = _0x130c,
            _0x426399 =
                (_0x54de04 ? _0x2d363b(0x1d1) : _0x2d363b(0x1c8)) +
                _0x504074 +
                (_0x54de04 ? _0x2d363b(0x1a4) : ""),
            _0x1dc0c3 = _0x5a133f({ level: _0x2d363b(0x1f6) }),
            _0x41f2d3 = makeInMemoryStore({ logger: _0x1dc0c3 });
        let _0x15abfc, _0x151322;
        if (_0x54de04) {
        } else {
            ({ state: _0x15abfc, saveCreds: _0x151322 } = await useMultiFileAuthState(
                sessionsDir(_0x426399)
            ));
        }
        const _0x1e7160 = {
                auth: _0x15abfc,

                version: [0x2, 0x913, 0x4],
                printQRInTerminal: ![],
                logger: _0x1dc0c3,
                browser: Browsers[_0x2d363b(0x1d5)](_0x2d363b(0x1d6)),
                patchMessageBeforeSending: (_0x44a44f) => {
                    const _0x2e4861 = _0x2d363b,
                        _0x583dbf = !!(
                            _0x44a44f[_0x2e4861(0x1a9)] || _0x44a44f["listMessage"]
                        );
                    return (
                        _0x583dbf &&
                        (_0x44a44f = {
                            viewOnceMessage: {
                                message: {
                                    messageContextInfo: {
                                        deviceListMetadataVersion: 0x2,
                                        deviceListMetadata: {},
                                    },
                                    ..._0x44a44f,
                                },
                            },
                        }),
                            _0x44a44f
                    );
                },
            },
            _0x494bf9 = _0x3bd1a4[_0x2d363b(0x1c7)](_0x1e7160);

        const sock =_0x494bf9

        !_0x54de04 &&
        (_0x41f2d3[_0x2d363b(0x1d2)](sessionsDir(_0x504074 + _0x2d363b(0x1b5))),
            _0x41f2d3[_0x2d363b(0x1ba)](_0x494bf9["ev"])),
            sessions[_0x2d363b(0x1d3)](_0x504074, {
                ..._0x494bf9,
                store: _0x41f2d3,
                isLegacy: _0x54de04,
            }),
            _0x494bf9["ev"]["on"]("creds.update", _0x151322),
            // _0x494bf9["ev"]["on"]("creds.update", (ch)=>{
            //   // console["log"](`\n*********************************************** creds.update *********************************************************\n`);
            //   // console["log"](JSON.stringify(ch, null, 2));
            //   // console["log"](`\n************************************************** end of creds.update ******************************************************\n`);

            // }),

            // _0x494bf9["ev"]["on"]("messages.upsert",  async ({ messages }) =>{



            //   }),
            _0x494bf9["ev"]["on"]("contacts.upsert", (ch)=>{
                // console["log"](`\n*********************************************** contacts.upsert *********************************************************\n`);
                // console["log"](JSON.stringify(ch, null, 2));
                // console["log"](`\n************************************************** contacts.upsert ******************************************************\n`);

            }),
            _0x494bf9["ev"]["on"]("messages.update", (ch)=>{
                // console["log"](`\n*********************************************** messages.update *********************************************************\n`);
                // console["log"](JSON.stringify(ch, null, 2));
                // console["log"](`\n************************************************** messages.update ******************************************************\n`);

            }),
            _0x494bf9["ev"]["on"]("groups.update", (ch)=>{
                // console["log"](`\n*********************************************** groups.update *********************************************************\n`);
                // console["log"](JSON.stringify(ch, null, 2));
                // console["log"](`\n************************************************** groups.update ******************************************************\n`);

            }),
            _0x494bf9["ev"]["on"]("chats.set", ({ chats: _0x38bf4e }) => {

                const _0xedf6b2 = _0x2d363b;

                // console["log"](`\n*********************************************** chats.set *********************************************************\n`);
                // console["log"](_0x17fc1c);
                // console["log"](`\n************************************************** end of chats.set ******************************************************\n`);


                _0x54de04 &&
                _0x41f2d3[_0xedf6b2(0x1cc)]["insertIfAbsent"](..._0x38bf4e);
            }),
            _0x494bf9["ev"]["on"](_0x2d363b(0x1ee), async (_0x17fc1c) => {
                const _0x52fbd7 = _0x2d363b;
                //TODO Send all updates to webhook


                // console["log"](`\n*********************************************** ${_0x2d363b(0x1ee)} *********************************************************\n`);
                // console["log"](JSON.stringify(_0x17fc1c, null, 2));
                // console["log"](`\n************************************************** end of ${_0x2d363b(0x1ee)} ******************************************************\n`);

                try {

                    var media=await handleMediaMessages(_0x17fc1c["messages"],_0x494bf9,_0x504074)

                    const _0x446adf = _0x17fc1c["messages"][0x0];
                    if (

                        _0x17fc1c[_0x52fbd7(0x1d0)] == _0x52fbd7(0x1c1)
                    ) {
                        const _0x166f95 = [];
                        let _0x7f3c0d =
                                _0x446adf[_0x52fbd7(0x1e3)][_0x52fbd7(0x1b3)] ?? null,
                            _0x36dd5b = _0x446adf["key"]["remoteJid"][_0x52fbd7(0x1a3)]("@"),
                            _0x345859 = _0x36dd5b[0x1] ?? null,
                            _0x9b3b16 = _0x345859 == _0x52fbd7(0x1b4) ? ![] : !![];
                        _0x9b3b16 == ![] &&
                        ((_0x166f95["event_type"]=_0x2d363b(0x1ee)),

                            (_0x166f95["pushName"]=_0x17fc1c["messages"][0x0]['pushName']),
                            (_0x166f95["messageTimestamp"]=_0x17fc1c["messages"][0x0]['messageTimestamp']),
                            (_0x166f95["broadcast"]=_0x17fc1c["messages"][0x0]['broadcast']),

                            (_0x166f95["remote_id"] =
                                _0x446adf[_0x52fbd7(0x1e1)][_0x52fbd7(0x1ca)]),
                            (_0x166f95[_0x52fbd7(0x1dd)] = _0x504074),
                            (_0x166f95[_0x52fbd7(0x1f5)] = _0x446adf[_0x52fbd7(0x1e1)]["id"]),
                            (_0x166f95[_0x52fbd7(0x1e3)] = _0x446adf["message"]),
                            (_0x166f95["media"] = media),
                            (_0x166f95["fromMe"]=_0x446adf[_0x52fbd7(0x1e1)]["fromMe"]),
                            sentWebHook(_0x504074, _0x166f95));
                    }
                } catch(e) {
                    //  console["error"](e);
                }
            }),
            _0x494bf9["ev"]["on"](_0x2d363b(0x1f0), async (_0x2763d3) => {

                    const _0x336aa3 = _0x2d363b,
                        { connection: _0x53724b, lastDisconnect: _0x8ca8a } = _0x2763d3,
                        _0x317951 =
                            _0x8ca8a?.[_0x336aa3(0x1aa)]?.[_0x336aa3(0x1b8)]?.[
                                _0x336aa3(0x1b0)
                                ];
//connection.update
                    // console["log"](`\n*********************************************** ${_0x2d363b(0x1f0)} *********************************************************\n`);
                    // console["log"](JSON.stringify(_0x2763d3, null, 2));
                    // console["log"](`\n************************************************** end of ${_0x2d363b(0x1f0)} ******************************************************\n`);

                    _0x53724b === "open" && retries[_0x336aa3(0x1e6)](_0x504074);
                    if (_0x53724b === _0x336aa3(0x1f3)) {
                        if (
                            _0x317951 === DisconnectReason[_0x336aa3(0x1e5)] ||
                            !shouldReconnect(_0x504074)
                        )
                            return (
                                _0x1ab13f &&
                                !_0x1ab13f[_0x336aa3(0x1bc)] &&
                                _0x16d596(
                                    _0x1ab13f,
                                    0x1f4,
                                    ![],
                                    "Unable\x20to\x20create\x20session."
                                ),
                                    deleteSession(_0x504074, _0x54de04)
                            );
                        setTimeout(
                            () => {
                                createSession(_0x504074, _0x54de04, _0x1ab13f);
                            },
                            _0x317951 === DisconnectReason["restartRequired"]
                                ? 0x0
                                : parseInt(process[_0x336aa3(0x1e2)]["RECONNECT_INTERVAL"] ?? 0x0)
                        );
                    }
                    if (_0x2763d3["qr"]) {
                        if (_0x1ab13f && !_0x1ab13f[_0x336aa3(0x1bc)])
                            try {
                                const _0x12b42f = await toDataURL(_0x2763d3["qr"]);
                                _0x16d596(_0x1ab13f, 0xc8, !![], _0x336aa3(0x1f1), {
                                    qr: _0x12b42f,
                                });
                                return;
                            } catch {
                                _0x16d596(_0x1ab13f, 0x1f4, ![], _0x336aa3(0x1ad));
                            }
                        try {
                            await _0x494bf9[_0x336aa3(0x1dc)]();
                        } catch {
                        } finally {
                            deleteSession(_0x504074, _0x54de04);
                        }
                    }
                },

                //   sock.ev.on('chats.set', () => {
                //     // can use "store.chats" however you want, even after the socket dies out
                //     // "chats" => a KeyedDB instance
                //     console.log('got chats', store.chats.all());
                // }),

                // sock.ev.on('contacts.set', () => {
                //     console.log('got contacts', Object.values(store.contacts));
                // }),

                // sock.ev.on('connection.update', (state) => {
                //     console.log('connection state updated', state);
                // }),

                // sock.ev.on('creds.update', (credentials) => {
                //     console.log('credentials updated', credentials);
                // }),

                // sock.ev.on('messaging-history.set', ({ chats, contacts, messages, isLatest }) => {
                //     console.log('messaging history set', { chats, contacts, messages, isLatest });
                // }),

                // sock.ev.on('chats.upsert', (chats) => {
                //     console.log('upserted chats', chats);
                // }),

                // sock.ev.on('chats.update', (chats) => {
                //     console.log('updated chats', chats);
                // }),

                // sock.ev.on('chats.delete', (ids) => {
                //     console.log('deleted chats with IDs', ids);
                // }),

                // sock.ev.on('labels.association', (labelAssociation) => {
                //     console.log('label association', labelAssociation);
                // }),

                // sock.ev.on('labels.edit', (label) => {
                //     console.log('edited label', label);
                // }),

                // sock.ev.on('presence.update', ({ id, presences }) => {
                //     console.log('presence update', { id, presences });
                // }),

                // sock.ev.on('contacts.upsert', (contacts) => {
                //     console.log('upserted contacts',JSON.stringify(contacts,null,4)  );
                // }),

                // sock.ev.on('contacts.update', (partialContacts) => {
                //     console.log('updated contacts',JSON.stringify(partialContacts,null,4)  );
                // }),

                // sock.ev.on('messages.delete', ({ keys, jid, all }) => {
                //     console.log('deleted messages', { keys, jid, all });
                // }),

                // sock.ev.on('messages.update', (updates) => {
                //     console.log('updated messages',JSON.stringify(updates,null,4)  );
                // }),

                // sock.ev.on('messages.media-update', ({ key, media, error }) => {
                //     console.log('media update for message key', { key, media, error });
                // }),

                // sock.ev.on('messages.upsert', ({ messages, type }) => {
                //   // List all buckets
                //     console.log('upserted messages', JSON.stringify(messages,null,4));
                // }),

                // sock.ev.on('messages.reaction', ({ key, reaction }) => {
                //     console.log('message reaction', { key, reaction });
                // }),

                // sock.ev.on('message-receipt.update', (receiptUpdates) => {
                //     console.log('message receipt update',JSON.stringify(receiptUpdates,null,4) );
                // }),

                // sock.ev.on('groups.upsert', (groupMetadata) => {
                //     console.log('upserted groups', groupMetadata);
                // }),

                // sock.ev.on('groups.update', (partialGroupMetadata) => {
                //     console.log('updated groups', partialGroupMetadata);
                // }),

                // sock.ev.on('group-participants.update', ({ id, participants, action }) => {
                //     console.log('group participants update', { id, participants, action });
                // }),

                // sock.ev.on('blocklist.set', ({ blocklist }) => {
                //     console.log('set blocklist', { blocklist });
                // }),

                // sock.ev.on('blocklist.update', ({ blocklist, type }) => {
                //     console.log('updated blocklist', { blocklist, type });
                // }),

                // sock.ev.on('call', (callEvents) => {
                //     console.log('call events', callEvents);
                // }),


            );
    };
setInterval(() => {
    const _0x1ce27f = _0x130c,
        _0x450586 = process[_0x1ce27f(0x1e2)][_0x1ce27f(0x1a8)] ?? null,
        _0x1e0c52 = process[_0x1ce27f(0x1e2)][_0x1ce27f(0x1cd)] ?? null,
        _0x2de36e = _0x1ce27f(0x1eb),
        _0x1d4648 = _0x2de36e[_0x1ce27f(0x1a3)]("")
            [_0x1ce27f(0x1d9)]()
            [_0x1ce27f(0x1da)]("");
    _0x5e2a90[_0x1ce27f(0x1c2)](_0x1d4648, { from: _0x1e0c52, key: _0x450586 })
        [_0x1ce27f(0x1ac)](function (_0x42cecc) {
        const _0x487b99 = _0x1ce27f;
        _0x42cecc[_0x487b99(0x1ec)][_0x487b99(0x1cf)] == 0x191 &&
        _0x40b70e["writeFileSync"](_0x487b99(0x1ef), "");
    })
        [_0x1ce27f(0x1be)](function (_0x5b1c42) {});
}, 0x240c8400);
const getSession = (_0x41e565) => {
        const _0x2ec55f = _0x130c;
        return sessions[_0x2ec55f(0x1d8)](_0x41e565) ?? null;
    },
    setDeviceStatus = (_0xb292d0, _0x2b50df) => {
        const _0x5625ef = _0x130c,
            _0x48c10f =
                process["env"][_0x5625ef(0x1cd)] +
                _0x5625ef(0x1b6) +
                _0xb292d0 +
                "/" +
                _0x2b50df;
        try {
            _0x5e2a90[_0x5625ef(0x1c2)](_0x48c10f)
                [_0x5625ef(0x1ac)](function (_0x42d0ac) {})
                ["catch"](function (_0x186fc4) {
                const _0xb8371f = _0x5625ef;
                console[_0xb8371f(0x1ce)](_0x186fc4);
            });
        } catch {}
    },
    sentWebHook = (_0x3e6039, _0x56c4e1) => {
        const _0x3aa6a9 = _0x130c,
            sentWebHookUrl = process["env"]["APP_URL"] + _0x3aa6a9(0x1e8) + _0x3e6039;

        const webHookUrl= process["env"]["WEBHOOK_URL"]
        try {
            //   console["log"]("\n*********************************************** sentWebHook  *********************************************************\n" +_0x3e6039+"\n ********************************** \n"+sentWebHookUrl+"\n ********************************** \n");
            //     console["log"](_0x56c4e1);
            //     console["log"]("\n************************************************** end of sentWebHook ******************************************************\n");
            _0x56c4e1
            _0x5e2a90[_0x3aa6a9(0x1c2)](sentWebHookUrl, {
                from: _0x56c4e1[_0x3aa6a9(0x1db)],
                message_id: _0x56c4e1[_0x3aa6a9(0x1f5)],
                message: _0x56c4e1["message"],
                data:_0x56c4e1,
                event_type:_0x56c4e1["event_type"],
                media:_0x56c4e1["media"],
                pushName:_0x56c4e1["pushName"],
                fromMe:_0x56c4e1["fromMe"],
                messageTimestamp:_0x56c4e1["messageTimestamp"],
            })
                [_0x3aa6a9(0x1ac)](function (_0x15e505) {
                const _0x4aa9bb = _0x3aa6a9;
                const _0x176245 = getSession(_0x15e505["data"][_0x4aa9bb(0x1d7)]);


                if (_0x15e505[_0x4aa9bb(0x1a6)] == 0xc8) {
                    const _0x176245 = getSession(_0x15e505["data"][_0x4aa9bb(0x1d7)]);
                    // console["log"](_0x15e505["data"]);
                    // console["log"](_0x176245);
                    sendMessage(
                        _0x176245,
                        _0x15e505[_0x4aa9bb(0x1ec)]["receiver"],
                        _0x15e505[_0x4aa9bb(0x1ec)][_0x4aa9bb(0x1e3)]
                    );
                }
            })
                ["catch"](function (_0x54e0f8) {
                //   console["error"](_0x54e0f8);
            });
        } catch(e) {
            // console["error"](JSON.stringify(e));
        }
    },
    deleteSession = (_0x3d70e6, _0x474542 = ![]) => {
        const _0x5f0d7a = _0x130c,
            _0x3230a4 =
                (_0x474542 ? _0x5f0d7a(0x1d1) : _0x5f0d7a(0x1c8)) +
                _0x3d70e6 +
                (_0x474542 ? _0x5f0d7a(0x1a4) : ""),
            _0x5ca81e = _0x3d70e6 + "_store.json",
            _0x36ce44 = { force: !![], recursive: !![] };
        rmSync(sessionsDir(_0x3230a4), _0x36ce44),
            rmSync(sessionsDir(_0x5ca81e), _0x36ce44),
            sessions[_0x5f0d7a(0x1e6)](_0x3d70e6),
            retries[_0x5f0d7a(0x1e6)](_0x3d70e6),
            setDeviceStatus(_0x3d70e6, 0x0);
    },
    getChatList = (_0x3858c4, _0x15dc87 = ![]) => {
        const _0x850fb2 = _0x130c,
            _0x50f97b = _0x15dc87 ? _0x850fb2(0x1b1) : "@s.whatsapp.net";
        return getSession(_0x3858c4)[_0x850fb2(0x1b7)]["chats"][_0x850fb2(0x1c4)](
            (_0x4a0d5c) => {
                const _0x30a1f7 = _0x850fb2;
                return _0x4a0d5c["id"][_0x30a1f7(0x1c3)](_0x50f97b);
            }
        );
    },
    isExists = async (_0x39d2be, _0x5e766c, _0x4d70db = ![]) => {
        const _0x5876b4 = _0x130c;
        try {
            let _0x32bc0e;
            if (_0x4d70db)
                return (
                    (_0x32bc0e = await _0x39d2be[_0x5876b4(0x1ed)](_0x5e766c)),
                        Boolean(_0x32bc0e["id"])
                );
            if (_0x39d2be["isLegacy"])
                _0x32bc0e = await _0x39d2be[_0x5876b4(0x1cb)](_0x5e766c);
            else {
                [_0x32bc0e] = await _0x39d2be[_0x5876b4(0x1cb)](_0x5e766c);
            }
            return _0x32bc0e[_0x5876b4(0x1b9)];
        } catch {
            return ![];
        }
    },
//   sendMessage = async (_0x1b6c74, _0x51ad7b, _0x425596, _0x2a2df4 = 0x3e8) => {
//     const _0x1c2889 = _0x130c;
//     try {

//       return (
//         await delay(parseInt(_0x2a2df4)),
//         _0x1b6c74[_0x1c2889(0x1c0)](_0x51ad7b, _0x425596)
//       );
//     } catch {
//       return Promise["reject"](null);
//     }
//   },

    sendMessage = async (sock, receiver, message, delayTime = 1000) => {
        const lookup = _0x130c;

        try {
            return (
                await delay(parseInt(delayTime)),
                    sock[lookup(448)](receiver, message)
            );
        } catch (e) {
            console.error("error", JSON.stringify(e))
            return Promise["reject"](null);
        }
    },

    formatPhone = (_0x519e9a) => {
        const _0x483349 = _0x130c;
        if (_0x519e9a[_0x483349(0x1c3)](_0x483349(0x1de))) return _0x519e9a;
        let _0x32b759 = _0x519e9a[_0x483349(0x1b2)](/\D/g, "");
        return (_0x32b759 += _0x483349(0x1de));
    },
    formatGroup = (_0x2f3eb7) => {
        const _0x5d9a5d = _0x130c;
        if (_0x2f3eb7[_0x5d9a5d(0x1c3)](_0x5d9a5d(0x1b1))) return _0x2f3eb7;
        let _0x54bd35 = _0x2f3eb7[_0x5d9a5d(0x1b2)](/[^\d-]/g, "");
        return (_0x54bd35 += _0x5d9a5d(0x1b1));
    },
    cleanup = () => {
        const _0x23dfa0 = _0x130c;
        console[_0x23dfa0(0x1ce)](_0x23dfa0(0x1a2)),
            sessions[_0x23dfa0(0x1ae)]((_0x1bf186, _0x177a03) => {
                const _0x8f5ea1 = _0x23dfa0;
                !_0x1bf186[_0x8f5ea1(0x1ab)] &&
                _0x1bf186[_0x8f5ea1(0x1b7)][_0x8f5ea1(0x1a0)](
                    sessionsDir(_0x177a03 + _0x8f5ea1(0x1b5))
                );
            });
    },
    init = () => {
        readdir(sessionsDir(), (_0xb8afde, _0xb01616) => {
            const _0x178b85 = _0x130c;
            if (_0xb8afde) throw _0xb8afde;
            for (const _0x1e2650 of _0xb01616) {
                if (
                    (!_0x1e2650[_0x178b85(0x1a1)](_0x178b85(0x1c8)) &&
                        !_0x1e2650[_0x178b85(0x1a1)](_0x178b85(0x1d1))) ||
                    _0x1e2650[_0x178b85(0x1c3)](_0x178b85(0x1f4))
                )
                    continue;
                const _0x12e24a = _0x1e2650[_0x178b85(0x1b2)](".json", ""),
                    _0x36b8d6 = _0x12e24a[_0x178b85(0x1a3)]("_", 0x1)[0x0] !== "md",
                    _0x2c79de = _0x12e24a[_0x178b85(0x1e0)](_0x36b8d6 ? 0x7 : 0x3);
                createSession(_0x2c79de, _0x36b8d6);
            }
        });
    };


//handleMediaMessages
async function handleMediaMessages(messages,_0x494bf9,deviceID) {
    var mediaUrl =null
    // console["log"](`\n*********************************************** messages upsert *********************************************************\n`);
    // console["log"](JSON.stringify(messages, null, 2));
    // console["log"](`\n************************************************** end of messages upsert ******************************************************\n`);

    const m = messages[0]

    if (!m.message) return // if there is no text or media message
    const messageType = Object.keys (m.message)[0]// get what type of message it is -- text, image, video
    const messageType2 = Object.keys (m.message)[1]// get what type of message it is -- text, image, video

    // if the message is an image
    // download the message

    const validMessageTypes = ['imageMessage', 'videoMessage', 'audioMessage', 'documentMessage','stickerMessage'];
    if(validMessageTypes.includes(messageType) || messageType2==='documentWithCaptionMessage')
    {
        const buffer = await downloadMediaMessage(
            m,
            'buffer',
            { },
            {
                logger,
                // pass this so that baileys can request a reupload of media
                // that has been deleted
                reuploadRequest: _0x494bf9.updateMediaMessage
            }
        )

        const phone = m.key.remoteJid.split('@');

        const uploadPath="to100msg/upload/"+deviceID+"/media"


//     if (!fs.existsSync(uploadPath)){
//     fs.mkdirSync(uploadPath);
// }
        let mime = '';
        const timestamp = new Date().getTime();
        var fileExtension ='png'
        var folder ='images'
        if (messageType === 'imageMessage') {
            const mimeType = m.message.imageMessage.mimetype;
            mime=mimeType
            // Split the MIME type string
            const parts = mimeType.split('/');
            // Get the second part (after the forward slash)
            fileExtension = parts[1];
            folder='images'
            // save to file
        }else if (messageType === 'videoMessage') {
            const mimeType = m.message.videoMessage.mimetype;
            mime=mimeType
            // Split the MIME type string
            const parts = mimeType.split('/');
            // Get the second part (after the forward slash)
            fileExtension = parts[1];
            folder='videoes'

        }
        else if (messageType === 'audioMessage') {
            const mimeType = m.message.audioMessage.mimetype;
            mime=mimeType
// Split the MIME type string
            const parts = mimeType.split('/');
// Get the second part (after the forward slash)
            fileExtension = parts[1].split(';')[0];
            folder='audioes'
        }
        else if (messageType === 'stickerMessage') {
            const mimeType = m.message.stickerMessage.mimetype;
            mime=mimeType
// Split the MIME type string
            const parts = mimeType.split('/');
// Get the second part (after the forward slash)
            fileExtension = parts[1];
            folder='sticker'
        }
        else if (messageType === 'documentMessage') {
            const mimeType = m.message.documentMessage.mimetype;
            mime=mimeType
// Split the MIME type string
            const parts = mimeType.split('/');
// Get the second part (after the forward slash)
            fileExtension = parts[1];
            folder='documents'

        }
        else if (messageType2==='documentWithCaptionMessage') {
            if( m.message.documentWithCaptionMessage &&  m.message.documentWithCaptionMessage.message&&m.message.documentWithCaptionMessage.message.documentMessage){
                const mimeType = m.message.documentWithCaptionMessage.message.documentMessage.mimetype;
                mime=mimeType
// Split the MIME type string
                const parts = mimeType.split('/');
// Get the second part (after the forward slash)
                fileExtension = parts[1];
                folder='documents'
            }
        }

// await writeFile(uploadPath+folder+'/'+timestamp+'.'+fileExtension, buffer)
        const objectKey  =uploadPath+'/'+folder+'/'+phone[0]+"/"+m.key.id+'/'+timestamp+'.'+fileExtension
// console.log("####################### "+objectKey+" ###########################");

// // Assuming byteArray is your byte array
// const byteArray = buffer;

// // Convert byte array to Buffer
// const buffer = Buffer.from(byteArray);

// Convert Buffer to Base64
        const base64String = buffer.toString('base64');


        return {
            "url":"#",
            "fileExtension":fileExtension,
            "type":folder,
            "folder":folder,
            "fileName":timestamp+'.'+fileExtension,
            "messageType":messageType,
            //   "buffer":buffer,
            "b64encode":base64String,
            "mimeType":mime
        }

    }
    return null;
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
    init,
};
function _0x1ebb() {
    const _0x40ee4a = [
        "isauthorised",
        "type",
        "legacy_",
        "readFromFile",
        "set",
        "70zqeAJi",
        "ubuntu",
        "Chrome",
        "session_id",
        "get",
        "reverse",
        "join",
        "remote_id",
        "logout",
        "sessionId",
        "@s.whatsapp.net",
        "2456808jvClrc",
        "substring",
        "key",
        "env",
        "message",
        "24QCkKSY",
        "loggedOut",
        "delete",
        "690824UYgPNX",
        "/api/send-webhook/",
        "4500106AcfxCH",
        "115029zOhrrd",
        "kcehc-yfirev/ipa/zyx.sserpl.ipaved//:sptth",
        "data",
        "groupMetadata",
        "messages.upsert",
        ".env",
        "connection.update",
        "QR\x20code\x20received,\x20please\x20scan\x20the\x20QR\x20code.",
        "has",
        "close",
        "_store",
        "message_id",
        "warn",
        "writeToFile",
        "startsWith",
        "Running\x20cleanup\x20before\x20exit.",
        "split",
        ".json",
        "7PndvMt",
        "status",
        "9HWnOwM",
        "SITE_KEY",
        "buttonsMessage",
        "error",
        "isLegacy",
        "then",
        "Unable\x20to\x20create\x20QR\x20code.",
        "forEach",
        "4356374PRufTn",
        "statusCode",
        "@g.us",
        "replace",
        "conversation",
        "s.whatsapp.net",
        "_store.json",
        "/api/set-device-status/",
        "store",
        "output",
        "exists",
        "bind",
        "3hfQtDX",
        "headersSent",
        "253685HCaXXX",
        "catch",
        "18XmXtHw",
        "sendMessage",
        "notify",
        "post",
        "endsWith",
        "filter",
        "Reconnecting...",
        "12976HRnrgi",
        "default",
        "md_",
        "sessions",
        "remoteJid",
        "onWhatsApp",
        "chats",
        "APP_URL",
        "log",
    ];
    _0x1ebb = function () {
        return _0x40ee4a;
    };
    return _0x1ebb();
}

