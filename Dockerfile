# Node.js এর স্টেবল ভার্সন ব্যবহার করুন
FROM node:20-slim

# ওয়ার্কিং ডিরেক্টরি
WORKDIR /app

# প্যাকেজ ফাইল কপি ও ইনস্টল
COPY package*.json ./
RUN npm install --production

# পুরো প্রজেক্ট কপি করা
COPY . .

# পোর্ট এক্সপোজ (রেন্ডার ৮০০০ বা ৩০০০ সাধারণত ডিফল্ট নেয়)
EXPOSE 3000

# রান করার কমান্ড (স্টার্ট স্ক্রিপ্ট নিশ্চিত করুন)
CMD ["npm", "start"]