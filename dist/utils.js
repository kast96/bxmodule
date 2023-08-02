"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.toUppercaseFirstChart = exports.wordToCaseVariants = exports.replaceModuleName = exports.replaceAllArray = void 0;
const replaceAllArray = (text, patterns, replacements) => {
    patterns.map((pattern, key) => {
        text = text.replaceAll(pattern, replacements[key]);
    });
    return text;
};
exports.replaceAllArray = replaceAllArray;
const replaceModuleName = (text, vendor, module) => {
    return (0, exports.replaceAllArray)(text, [
        ...(0, exports.wordToCaseVariants)('vendor').map(item => '{' + item + '}'),
        ...(0, exports.wordToCaseVariants)('module').map(item => '{' + item + '}')
    ], [
        ...(0, exports.wordToCaseVariants)(vendor),
        ...(0, exports.wordToCaseVariants)(module)
    ]);
};
exports.replaceModuleName = replaceModuleName;
const wordToCaseVariants = (word) => {
    return [
        word.toLowerCase(),
        (0, exports.toUppercaseFirstChart)(word),
        word.toUpperCase()
    ];
};
exports.wordToCaseVariants = wordToCaseVariants;
const toUppercaseFirstChart = (word) => {
    const firstLetter = word.charAt(0);
    const firstLetterCap = firstLetter.toUpperCase();
    const remainingLetters = word.slice(1);
    return firstLetterCap + remainingLetters;
};
exports.toUppercaseFirstChart = toUppercaseFirstChart;
//# sourceMappingURL=utils.js.map