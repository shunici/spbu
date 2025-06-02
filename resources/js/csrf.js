import $axios from './api.js'
import Cookie from "js-cookie";

export default {
  getCookie() {
    let token = Cookie.get("XSRF-TOKEN");

    if (token) {
      return new Promise(resolve => {
        resolve(token);
      });
    }

    return $axios.get("/csrf-cookie");
  }
};