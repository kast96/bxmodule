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
exports.create = void 0;
const middlewares_1 = require("../middlewares");
const utils_1 = require("../utils");
const fs = require('fs');
const path = require('path');
var readline = require('readline');
var rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
});
const create = (name) => __awaiter(void 0, void 0, void 0, function* () {
    const [vendor, module] = name.split('.');
    if (!vendor || !module) {
        console.log(`Название модуля должено состоять из вендорного кода и кода модуля разделенные точкой: vendor.module`);
        return;
    }
    const inputPath = '../../source/base';
    const outputPath = `${vendor}.${module}`;
    const packagesPath = '../../source/packages';
    let moduleName = 'Экспансия: Проект';
    let moduleDescription = 'Модуль для работы сайта компании Проект';
    let vendorName = 'Экспансия';
    let vendorUri = 'https://ewp.ru';
    let packages = [];
    moduleName = (yield (0, utils_1.answer)(rl, `Название модуля [${moduleName}]: `)) || moduleName;
    moduleDescription = (yield (0, utils_1.answer)(rl, `Описание модуля [${moduleDescription}]: `)) || moduleDescription;
    vendorName = (yield (0, utils_1.answer)(rl, `Разработчик модуля [${vendorName}]: `)) || vendorName;
    vendorUri = (yield (0, utils_1.answer)(rl, `URI адрес разработчика [${vendorUri}]: `)) || vendorUri;
    let packagesList = getPackages(packagesPath);
    for (const packageItem of packagesList) {
        let value = yield (0, utils_1.answer)(rl, `Подключить пакет "${packageItem.name}"? [y/n]: `);
        if (value == 'y')
            packages.push(packageItem);
    }
    const [packageIncludes, packageReplacements] = installPackages(packages, outputPath, vendor, module);
    //base module
    copyFolderSync(inputPath, outputPath, [
        middlewares_1.replaceDateMiddleware,
        (0, middlewares_1.replaceMiddleware)(['{moduleName}', '{moduleDescription}', '{vendorName}', '{vendorUri}'], [moduleName, moduleDescription, vendorName, vendorUri]),
        (0, middlewares_1.replaceIncludesMiddleware)(packageIncludes, packageReplacements),
        middlewares_1.clearIncludesMiddleware,
        (0, middlewares_1.replaceModuleNameMiddleware)(vendor, module),
    ]);
    rl.close();
    console.log(`Создание модуля ${vendor}.${module} завершено`);
});
exports.create = create;
const createDirectoryPath = (path) => {
    path.split('/').reduce((directories, directory) => {
        directories += `${directory}/`;
        if (!fs.existsSync(directories)) {
            fs.mkdirSync(directories);
        }
        return directories;
    }, '');
};
const copyFolderSync = (input, output, middlewares) => {
    let files = getFolderStructure(input);
    files.map(file => {
        let absoluteFile = path.join(__dirname, input, file);
        createDirectoryPath(path.dirname(`${output}/${file}`));
        let text = fs.readFileSync(absoluteFile, 'utf8');
        text = (middlewares === null || middlewares === void 0 ? void 0 : middlewares.reduce((text, middleware) => {
            return middleware(text);
        }, text)) || text;
        fs.writeFileSync(`${output}/${file}`, text);
    });
};
const getFolderStructure = (folder) => {
    let files = [];
    const absoluteFolder = path.join(__dirname, folder);
    if (fs.lstatSync(absoluteFolder).isDirectory()) {
        fs.readdirSync(absoluteFolder).forEach((itemName) => {
            const itemPath = `${folder}/${itemName}`;
            if (fs.lstatSync(path.join(__dirname, itemPath)).isDirectory()) {
                files = [...files, ...getFolderStructure(itemPath).map(subItemName => `${itemName}/${subItemName}`)];
            }
            else {
                files.push(itemName);
            }
        });
    }
    return files;
};
const getPackages = (folder) => {
    let packages = [];
    const absoluteFolder = path.join(__dirname, folder);
    if (fs.lstatSync(absoluteFolder).isDirectory()) {
        fs.readdirSync(absoluteFolder).forEach((itemName) => {
            const itemPath = `${folder}/${itemName}`;
            const absoluteItemPath = path.join(__dirname, itemPath);
            if (fs.lstatSync(absoluteItemPath).isDirectory()) {
                fs.readdirSync(absoluteItemPath).forEach((subItemName) => {
                    if (subItemName == 'config.json') {
                        let config = JSON.parse(fs.readFileSync(`${absoluteItemPath}/${subItemName}`, 'utf8'));
                        packages.push(Object.assign({ path: itemPath, folder: itemName }, config));
                    }
                });
            }
        });
    }
    return packages;
};
const installPackages = (packages, output, vendor, module) => {
    let includes = [];
    let replacements = [];
    packages.forEach(packageItem => {
        copyFolderSync(`${packageItem.path}/files`, output, [
            (0, middlewares_1.replaceModuleNameMiddleware)(vendor, module),
        ]);
        if (packageItem.include) {
            for (const key in packageItem.include) {
                if (Object.prototype.hasOwnProperty.call(packageItem.include, key)) {
                    const includePath = packageItem.include[key];
                    let text = fs.readFileSync(path.join(__dirname, `${packageItem.path}/${includePath}`), 'utf8');
                    includes.push(key);
                    replacements.push(text);
                }
            }
        }
    });
    return [includes, replacements];
};
//# sourceMappingURL=create.js.map