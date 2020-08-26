"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const childProcess = require("child_process");
function runPowershell(s, isFile) {
    return new Promise((resolve, reject) => {
        let child = childProcess.exec('powershell -' + (isFile ? 'File' : 'Command') + ' ' + s, {}, (e, stdout, stderr) => {
            if (e)
                return reject(e);
            if (stderr)
                return reject(stderr);
            resolve(stdout.trim());
        });
        child.stdin.end();
    });
}
exports.runPowershell = runPowershell;
;
