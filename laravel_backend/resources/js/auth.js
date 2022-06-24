import axios from 'axios';

// TODO: Discuss with employers about their preference. Ideally tokens must
// be kept in httpOnly, secure, samesite cookies instead of like this
// as all other references in laravel implement sending the Bearer
// token like this (saved in local storage, resent as header).
class Auth {
    constructor() {
        this.token = window.localStorage.getItem('token');
        const userStr = window.localStorage.getItem('user');
        this.user = userStr ? JSON.parse(userStr) : null;

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
        }
    }

    async login(email, password) {
        const { MIX_OAUTH_CLIENT_ID, MIX_OAUTH_CLIENT_SECRET } = process.env;
        // Let it fail
        // TODO: let's create a generic exception catch later that displays an unexpected error
        // in the case of 500s        
        const tokenRes = (await axios.post("/oauth/token", {
            grant_type: "password",
            client_id: MIX_OAUTH_CLIENT_ID,
            client_secret: MIX_OAUTH_CLIENT_SECRET,
            username: email,
            password: password,
        })).data;

        const token = tokenRes.access_token
        const user = (await this.fetchCurrentUser(token));
        if (user) {
            window.localStorage.setItem('token', token);
            window.localStorage.setItem('user', JSON.stringify(user));
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

            this.token = token;
            this.user = user;
        } else {
            console.error('The user was undefined even after a valid login')
        }
    }
    check() {
        return !!this.token;
    }

    getCurrentUser() {
        return this.user
    }

    async fetchCurrentUser(token) {
        const res = await axios.get("/api/me", { headers: { 'Authorization': `Bearer ${token}` } })
        this.user = res.data

        return this.user
    }

    logout() {
        // window.localStorage.clear();
        window.localStorage.removeItem('token');
        window.localStorage.removeItem('user');
        this.user = null;
    }
}
export default new Auth();