"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.clearIncludesMiddleware = exports.replaceIncludesMiddleware = exports.replaceMiddleware = exports.replaceDateMiddleware = exports.replaceModuleNameMiddleware = void 0;
const utils_1 = require("./utils");
const replaceModuleNameMiddleware = (vendor, module) => (text) => {
    return (0, utils_1.replaceModuleName)(text, vendor, module);
};
exports.replaceModuleNameMiddleware = replaceModuleNameMiddleware;
const replaceDateMiddleware = (text) => {
    const date = new Date();
    const dateString = `${date.getFullYear()}-${("0" + (date.getMonth() + 1)).slice(-2)}-${('0' + date.getDate()).slice(-2)} ${("0" + date.getHours()).slice(-2)}:${("0" + date.getMinutes()).slice(-2)}:${("0" + date.getSeconds()).slice(-2)}`;
    return text.replaceAll('{date}', dateString);
};
exports.replaceDateMiddleware = replaceDateMiddleware;
const replaceMiddleware = (patterns, replacements) => (text) => {
    return (0, utils_1.replaceAllArray)(text, patterns, replacements);
};
exports.replaceMiddleware = replaceMiddleware;
const replaceIncludesMiddleware = (patterns, replacements) => (text) => {
    return (0, utils_1.replaceAllArray)(text, patterns.map(pattern => `{include:${pattern}}`), replacements.map((replacement, key) => `${replacement}\n{include:${patterns[key]}}`));
};
exports.replaceIncludesMiddleware = replaceIncludesMiddleware;
const clearIncludesMiddleware = (text) => {
    return text.replaceAll(/[\n\t]*{include:[^}]*}/g, '');
};
exports.clearIncludesMiddleware = clearIncludesMiddleware;
//# sourceMappingURL=middlewares.js.map