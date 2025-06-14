<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

const show = ref(false);
const notifications = ref([]);
const connected = ref(false);
const unread = ref(0);
const loading = ref(false);

const toggle = () => {
  show.value = !show.value;
  if (show.value && unread.value > 0) markAllRead();
};

const timeAgo = (date) => {
  const mins = Math.floor((new Date() - new Date(date)) / 60000);
  if (mins < 1) return 'baru';
  if (mins < 60) return `${mins}m`;
  if (mins < 1440) return `${Math.floor(mins/60)}j`;
  return `${Math.floor(mins/1440)}h`;
};

const getNotifications = async () => {
  if (loading.value) return;
  loading.value = true;
  try {
    const { data } = await axios.get('http://127.0.0.1:8090/api/notifikasis?limit=15');
    notifications.value = (data?.data || data || []).map(n => ({ ...n, time: timeAgo(n.created_at) }));
    unread.value = notifications.value.filter(n => !n.read).length;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const markRead = async (id) => {
  try {
    await axios.put(`http://127.0.0.1:8090/api/notifikasis/${id}/read`);
    const n = notifications.value.find(x => x.id === id);
    if (n) { n.read = true; unread.value--; }
  } catch (e) {}
};

const markAllRead = async () => {
  try {
    await axios.put('http://127.0.0.1:8090/api/notifikasis/mark-all-read');
    notifications.value.forEach(n => n.read = true);
    unread.value = 0;
  } catch (e) {}
};

const handleNotification = (data) => {
  const n = { ...data, id: data.id || Date.now(), time: 'baru', read: false };
  notifications.value.unshift(n);
  unread.value++;
  toast(`${n.title}: ${n.message}`, { type: 'warning', autoClose: 4000 });
};

let echo = null;

onMounted(() => {
  getNotifications();
  if (window.Echo) {
    window.Echo.connector.pusher.connection.bind('connected', () => connected.value = true);
    window.Echo.connector.pusher.connection.bind('disconnected', () => connected.value = false);
    echo = window.Echo.channel('stock-channel').listen('.stock.minimum', handleNotification);
  }
  setInterval(getNotifications, 60000);
});

onUnmounted(() => {
  if (echo) echo.stopListening('.stock.minimum');
});
</script>

<template>
  <div class="notification-wrapper">
    <button
      @click="toggle"
      class="notification-btn"
      :title="`${unread} notifikasi`"
    >
      <i class="ph-bell"></i>
      <span v-if="unread" class="badge">{{ unread > 9 ? '9+' : unread }}</span>
      <span class="status" :class="{ connected }"></span>
    </button>

    <div v-if="show" class="notification-dropdown">
      <!-- Header -->
      <div class="header">
        <span>Notifikasi</span>
        <button @click="getNotifications" :disabled="loading" class="refresh-btn">
          <i :class="loading ? 'ph-spinner ph-spin' : 'ph-arrow-clockwise'"></i>
        </button>
      </div>

      <!-- List -->
      <div class="list">
        <div v-if="loading && !notifications.length" class="loading">
          <div class="spinner"></div>
        </div>

        <div v-else-if="notifications.length" class="items">
          <div
            v-for="n in notifications"
            :key="n.id"
            class="item"
            :class="{ unread: !n.read }"
            @click="markRead(n.id)"
          >
            <div class="icon" :class="{ unread: !n.read }">
              <i class="ph-warning"></i>
            </div>
            <div class="content">
              <div class="title">{{ n.title }}</div>
              <div class="message">{{ n.message }}</div>
            </div>
            <div class="meta">
              <span class="time">{{ n.time }}</span>
              <span v-if="!n.read" class="dot"></span>
            </div>
          </div>
        </div>

        <div v-else class="empty">
          <i class="ph-bell-slash"></i>
          <span>Belum ada notifikasi</span>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="unread" class="footer">
        <button @click="markAllRead" class="mark-all-btn">Tandai semua dibaca</button>
      </div>
    </div>

    <!-- Overlay untuk mobile -->
    <div v-if="show" class="overlay" @click="show = false"></div>
  </div>
</template>

<style scoped>
.notification-wrapper {
  position: relative;
}

.notification-btn {
  position: relative;
  background: none;
  border: none;
  padding: 8px;
  border-radius: 50%;
  cursor: pointer;
  transition: background 0.2s;
}

.notification-btn:hover {
  background: rgba(0,0,0,0.05);
}

.notification-btn i {
  font-size: 20px;
  color: #6c757d;
}

.badge {
  position: absolute;
  top: -2px;
  right: -2px;
  background: #dc3545;
  color: white;
  font-size: 10px;
  font-weight: 600;
  min-width: 16px;
  height: 16px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
}

.status {
  position: absolute;
  bottom: 0;
  right: 2px;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #ffc107;
  transition: background 0.2s;
}

.status.connected {
  background: #28a745;
}

.notification-dropdown {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  width: 300px;
  max-height: 400px;
  z-index: 1000;
  overflow: hidden;
}

.header {
  display: flex;
  justify-content: between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #e9ecef;
  font-weight: 600;
  font-size: 14px;
}

.refresh-btn {
  background: none;
  border: none;
  padding: 4px;
  cursor: pointer;
  border-radius: 4px;
  color: #6c757d;
  margin-left: auto;
}

.refresh-btn:hover {
  background: #f8f9fa;
}

.list {
  max-height: 280px;
  overflow-y: auto;
}

.loading {
  display: flex;
  justify-content: center;
  padding: 24px;
}

.spinner {
  width: 20px;
  height: 20px;
  border: 2px solid #e9ecef;
  border-top-color: #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.items {
  display: flex;
  flex-direction: column;
}

.item {
  display: flex;
  padding: 12px 16px;
  cursor: pointer;
  transition: background 0.2s;
  border-bottom: 1px solid #f8f9fa;
}

.item:hover {
  background: #f8f9fa;
}

.item.unread {
  background: rgba(0,123,255,0.05);
}

.icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #6c757d;
  color: white;
  font-size: 12px;
  flex-shrink: 0;
  margin-right: 12px;
}

.icon.unread {
  background: #007bff;
}

.content {
  flex: 1;
  min-width: 0;
}

.title {
  font-size: 13px;
  font-weight: 600;
  color: #212529;
  margin-bottom: 2px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.message {
  font-size: 12px;
  color: #6c757d;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  margin-left: 8px;
}

.time {
  font-size: 11px;
  color: #adb5bd;
  margin-bottom: 4px;
}

.dot {
  width: 6px;
  height: 6px;
  background: #007bff;
  border-radius: 50%;
}

.empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 32px 16px;
  color: #adb5bd;
}

.empty i {
  font-size: 32px;
  margin-bottom: 8px;
}

.empty span {
  font-size: 13px;
}

.footer {
  padding: 12px 16px;
  border-top: 1px solid #e9ecef;
  text-align: center;
}

.mark-all-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 6px 16px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: background 0.2s;
}

.mark-all-btn:hover {
  background: #0056b3;
}

.overlay {
  display: none;
}

/* Custom scrollbar */
.list::-webkit-scrollbar {
  width: 4px;
}

.list::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.list::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .notification-dropdown {
    position: fixed;
    top: 60px !important;
    left: 12px;
    right: 12px;
    width: auto;
    max-height: calc(100vh - 80px);
  }

  .overlay {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.3);
    z-index: 999;
  }

  .item {
    padding: 16px;
  }

  .icon {
    width: 32px;
    height: 32px;
    margin-right: 16px;
  }

  .title {
    font-size: 14px;
  }

  .message {
    font-size: 13px;
  }
}

@media (max-width: 480px) {
  .notification-dropdown {
    left: 8px;
    right: 8px;
  }
}
</style>
