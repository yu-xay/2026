// server/server.js
import { WebSocketServer } from 'ws';

const wss = new WebSocketServer({ port: 2525 });

console.log('WebSocket 服务器已启动 ws://localhost:2525');

// ============ 正确的广播函数 ============
function broadcast(data, excludeWs = null) {
    const str = JSON.stringify(data);
    wss.clients.forEach(client => {
        if (client.readyState === client.OPEN && client !== excludeWs) {
            client.send(str);
        }
    });
}

// ============ 每 2 秒广播一次系统时间（全局只执行一次）============
setInterval(() => {
    broadcast({
        type: 'message',
        name: '系统时钟',
        message: `当前时间：${new Date().toLocaleString()}`
    });
    // 注意：这里不要传 excludeWs！要发给所有人
}, 2000);

// ============ 连接处理 ============
wss.on('connection', (ws, req) => {
    const ip = req.headers['x-forwarded-for']?.split(',')[0] || req.socket.remoteAddress;
    console.log(`新客户端连接: ${ip}`);

    // 1. 私发欢迎消息（只给自己）
    ws.send(JSON.stringify({
        type: 'system',
        message: '欢迎加入聊天室！',
        time: new Date().toLocaleString()
    }));

    // 2. 广播上线通知（排除自己，别人能看到你上线）
    broadcast({
        type: 'system',
        message: `用户${ip} 加入了聊天室`,
        online: wss.clients.size
    }, ws);   // ← 正确传了 ws，排除自己

    // 3. 接收消息并广播
    ws.on('message', (data) => {
        try {
            const msg = JSON.parse(data.toString());
            const packet = {
                type: 'message',
                name: msg.name || '匿名',
                message: msg.message || msg,
                time: new Date().toLocaleString()
            };
            broadcast(packet); // 发给所有人，包括自己
            console.log(`收到 [${packet.name}]: ${packet.message}`);
        } catch (e) {
            ws.send(JSON.stringify({ type: 'error', message: '消息格式错误' }));
        }
    });

    // 4. 断开连接
    ws.on('close', () => {
        console.log(`客户端 ${ip} 已断开`);
        broadcast({
            type: 'system',
            message: `用户${ip} 离开了聊天室`,
            online: wss.clients.size
        });
        // 断开时不需要排除任何人，所有人都能看到
    });
});

console.log('服务器就绪，每2秒会向所有客户端推送一次时间');
