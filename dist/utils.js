"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.answer = exports.toUppercaseFirstChart = exports.wordToCaseVariants = exports.replaceModuleName = exports.replaceAllArray = void 0;
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
const answer = (readline, question) => __awaiter(void 0, void 0, void 0, function* () {
    return yield new Promise(resolve => {
        readline.question(question, resolve);
    });
});
exports.answer = answer;
//# sourceMappingURL=utils.js.map