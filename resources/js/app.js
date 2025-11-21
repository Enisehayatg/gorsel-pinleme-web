import './bootstrap';

// Firebase initialization
import { initializeApp } from "firebase/app";
import { getAuth } from "firebase/auth";

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyAV1zo2X6JVzkuqpgavBpNboFnD1IXowP4",
    authDomain: "gorselpinleme.firebaseapp.com",
    projectId: "gorselpinleme",
    storageBucket: "gorselpinleme.firebasestorage.app",
    messagingSenderId: "406563332172",
    appId: "1:406563332172:web:79f3016f77eb9e78b854e9",
    measurementId: "G-KDVQG3DL02"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

export { auth };
