import axios from 'axios';

const ENQUIRY = '/enquiry';

const http = axios.create({
    baseURL: process.env.VUE_APP_BACKEND_URL,
    timeout: 5000,
});

export default {
    enquirySave(payload) {
        return http.post(ENQUIRY, payload);
    }
}