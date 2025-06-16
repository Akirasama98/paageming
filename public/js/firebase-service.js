// Firebase Web SDK Operations
// This file provides Firebase functions for frontend use

class FirebaseService {
    constructor() {
        this.config = {
            apiKey: "AIzaSyCcMl4S3w_rm4s7BUXKzav3Hxp1ZAvkr2o",
            authDomain: "panenku-cd8ea.firebaseapp.com",
            databaseURL: "https://panenku-cd8ea-default-rtdb.firebaseio.com",
            projectId: "panenku-cd8ea",
            storageBucket: "panenku-cd8ea.firebasestorage.app",
            messagingSenderId: "157884916998",
            appId: "1:157884916998:web:b99bc9d3691c732927aa87",
            measurementId: "G-Z52C5DKY0C"
        };
        
        this.app = null;
        this.database = null;
        this.storage = null;
        this.isInitialized = false;
    }

    async initialize() {
        try {
            // Import Firebase modules dynamically
            const { initializeApp } = await import('https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js');
            const { getDatabase, ref, onValue, off, get, child } = await import('https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js');
            const { getStorage } = await import('https://www.gstatic.com/firebasejs/10.7.1/firebase-storage.js');

            // Initialize Firebase
            this.app = initializeApp(this.config);
            this.database = getDatabase(this.app);
            this.storage = getStorage(this.app);
            
            // Store Firebase functions for later use
            this.ref = ref;
            this.onValue = onValue;
            this.off = off;
            this.get = get;
            this.child = child;
            
            this.isInitialized = true;
            console.log('✅ Firebase Web SDK initialized successfully');
            return true;
        } catch (error) {
            console.error('❌ Firebase initialization failed:', error);
            return false;
        }
    }

    async getProducts() {
        if (!this.isInitialized) {
            await this.initialize();
        }

        return new Promise((resolve, reject) => {
            try {
                const productsRef = this.ref(this.database, 'products');
                this.onValue(productsRef, (snapshot) => {
                    const data = snapshot.val();
                    if (data) {
                        // Convert object to array with IDs
                        const products = Object.keys(data).map(key => ({
                            id: key,
                            ...data[key]
                        }));
                        resolve(products);
                    } else {
                        resolve([]);
                    }
                }, (error) => {
                    reject(error);
                });
            } catch (error) {
                reject(error);
            }
        });
    }

    async getProduct(productId) {
        if (!this.isInitialized) {
            await this.initialize();
        }

        return new Promise((resolve, reject) => {
            try {
                const productRef = this.ref(this.database, `products/${productId}`);
                this.onValue(productRef, (snapshot) => {
                    const data = snapshot.val();
                    resolve(data);
                }, (error) => {
                    reject(error);
                });
            } catch (error) {
                reject(error);
            }
        });
    }

    async listenToProducts(callback) {
        if (!this.isInitialized) {
            await this.initialize();
        }

        try {
            const productsRef = this.ref(this.database, 'products');
            this.onValue(productsRef, (snapshot) => {
                const data = snapshot.val();
                if (data) {
                    const products = Object.keys(data).map(key => ({
                        id: key,
                        ...data[key]
                    }));
                    callback(products);
                } else {
                    callback([]);
                }
            });
        } catch (error) {
            console.error('Error listening to products:', error);
        }
    }

    async testConnection() {
        if (!this.isInitialized) {
            await this.initialize();
        }

        return new Promise((resolve, reject) => {
            try {
                const testRef = this.ref(this.database, 'test/frontend-connection');
                this.onValue(testRef, (snapshot) => {
                    resolve({
                        connected: true,
                        data: snapshot.val(),
                        timestamp: new Date().toISOString()
                    });
                }, (error) => {
                    reject(error);
                });
            } catch (error) {
                reject(error);
            }
        });
    }
}

// Create global instance
window.firebaseService = new FirebaseService();
