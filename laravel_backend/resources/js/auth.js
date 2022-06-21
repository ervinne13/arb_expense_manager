import axios from 'axios';

// TODO: Discuss with employers about their preference. Ideally tokens must
// be kept in httpOnly, secure, samesite cookies instead of like this
// as all other references in laravel implement sending the Bearer
// token like this (saved in local storage, resent as header).


const getCurrentUser = async () => {
    // No catch, let it fail
    const res = await axios.get("/api/me");
    return res.data
}

export default {
    getCurrentUser
}

// class Auth {
//     constructor() {
//         this.token = window.localStorage.getItem('token');
//         let userData = window.localStorage.getItem('user');
//         this.user = userData ? JSON.parse(userData) : null;

//         if (this.token) {
//             axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
//         }
//     }
//     login(token, user) {
//         window.localStorage.setItem('token', token);
//         window.localStorage.setItem('user', JSON.stringify(user));
//         axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

//         this.token = token;
//         this.user = user;
//     }
//     check() {
//         return !!this.token;
//     }
//     logout() {
//         // window.localStorage.clear();
//         window.localStorage.removeItem('token');
//         window.localStorage.removeItem('user');
//         this.user = null;
//     }
// }
// export default new Auth();