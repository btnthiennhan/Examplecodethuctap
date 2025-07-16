const Bull = require('bull');
const express = require('express');
const app = express();

app.use(express.json());

// Kết nối Bull với Redis
const orderQueue = new Bull('order-queue', {
  redis: { host: 'localhost', port: 6379 },
});

// API nhận dữ liệu từ PHP và đẩy vào queue
app.post('/add-order', async (req, res) => {
  try {
    await orderQueue.add(req.body);
    res.json({ success: true });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

app.listen(3001, () => {
  console.log('Queue server running on http://localhost:3001');
});