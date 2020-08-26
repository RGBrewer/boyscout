"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const os = require("os");
const which_1 = require("which");
exports.hasPowershell = (function () {
    try {
        return which_1.which.sync('powershell');
    }
    catch (e) {
        return false;
    }
}());
exports.platform = process.env.TEST_PLAT || os.platform();
exports.isWindows = (exports.platform.indexOf('win') === 0);
exports.isOSX = (exports.platform.indexOf('darwin') === 0);
