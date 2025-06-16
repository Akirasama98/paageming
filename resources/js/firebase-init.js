// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getDatabase, ref, onValue, off } from "firebase/database";
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL } from "firebase/storage";

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCcMl4S3w_rm4s7BUXKzav3Hxp1ZAvkr2o",
  authDomain: "panenku-cd8ea.firebaseapp.com",
  databaseURL: "https://panenku-cd8ea-default-rtdb.firebaseio.com",
  projectId: "panenku-cd8ea",
  storageBucket: "panenku-cd8ea.firebasestorage.app",
  messagingSenderId: "157884916998",
  appId: "1:157884916998:web:b99bc9d3691c732927aa87",
  measurementId: "G-Z52C5DKY0C"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const database = getDatabase(app);
const storage = getStorage(app);

// Export Firebase services for use in other modules
export { app, analytics, database, storage, ref, onValue, off, storageRef, uploadBytes, getDownloadURL };
