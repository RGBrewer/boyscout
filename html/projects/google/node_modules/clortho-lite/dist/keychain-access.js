"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const os_features_1 = require("./os-features");
const osx_keychain_manager_1 = require("./osx-keychain-manager");
const powershell_keychain_manager_1 = require("./powershell-keychain-manager");
const in_memory_keychain_manager_1 = require("./in-memory-keychain-manager");
exports.keychain = (function () {
    if (os_features_1.isOSX) {
        return new osx_keychain_manager_1.OSX_Keychain();
    }
    if (os_features_1.isWindows && os_features_1.hasPowershell) {
        return new powershell_keychain_manager_1.PowershellKeychainManager();
    }
    return new in_memory_keychain_manager_1.InMemoryKeychainManager();
})();
