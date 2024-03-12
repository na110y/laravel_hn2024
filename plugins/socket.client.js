import Vue from 'vue'
import VueSocketIO from 'vue-socket.io'
import io from 'socket.io-client';
const socket = io('http://localhost:3000');

export default ({ app }, inject) => {
    inject('socket', socket);
};