import { replaceDateMiddleware, replaceModuleNameMiddleware, replaceMiddleware, replaceIncludesMiddleware, clearIncludesMiddleware } from "../middlewares"
import { PackageConfigType } from "../types";
import { answer } from "../utils"

const fs = require('fs')
const path = require('path')
var readline = require('readline');

var rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

export const create = async (name: string) => {
	const [vendor, module] = name.split('.')
	if (!vendor || !module) {
		console.log(`Название модуля должено состоять из вендорного кода и кода модуля разделенные точкой: vendor.module`)
		return
	}

	const inputPath = '../../source/base';
	const outputPath = `${vendor}.${module}`
	const packagesPath = '../../source/packages';

	let moduleName = 'Экспансия: Проект'
	let moduleDescription = 'Модуль для работы сайта компании Проект'
	let vendorName = 'Экспансия'
	let vendorUri = 'https://ewp.ru'
	let packages = [];

	moduleName = await answer(rl, `Название модуля [${moduleName}]: `) || moduleName
	moduleDescription = await answer(rl, `Описание модуля [${moduleDescription}]: `) || moduleDescription
	vendorName = await answer(rl, `Разработчик модуля [${vendorName}]: `) || vendorName
	vendorUri = await answer(rl, `URI адрес разработчика [${vendorUri}]: `) || vendorUri
	
	let packagesList = getPackages(packagesPath)
	for (const packageItem of packagesList) {
		let value = await answer(rl, `Подключить пакет "${packageItem.name}"? [y/n]: `)
		if (value == 'y') packages.push(packageItem)
	}

	const [packageIncludes, packageReplacements] = installPackages(packages, outputPath, vendor, module)

	//base module
	copyFolderSync(
		inputPath,
		outputPath,
		[
			replaceDateMiddleware,
			replaceMiddleware(
				['{moduleName}', '{moduleDescription}', '{vendorName}', '{vendorUri}'],
				[moduleName, moduleDescription, vendorName, vendorUri]
			),
			replaceIncludesMiddleware(packageIncludes, packageReplacements),
			clearIncludesMiddleware,
			replaceModuleNameMiddleware(vendor, module),
		]
	);

	rl.close()

	console.log(`Создание модуля ${vendor}.${module} завершено`)
}

const createDirectoryPath = (path: string) => {
	path.split('/').reduce(
		(directories, directory) => {
			directories += `${directory}/`

			if (!fs.existsSync(directories)) {
				fs.mkdirSync(directories)
			}

			return directories;
		},
		'',
	)
}

const copyFolderSync = (input: string, output: string, middlewares?: Array<(text: string) => string>) => {
	let files = getFolderStructure(input)
	
	files.map(file => {
		let absoluteFile = path.join(__dirname, input, file)

		createDirectoryPath(path.dirname(`${output}/${file}`))
		let text = fs.readFileSync(absoluteFile, 'utf8')
		
		text = middlewares?.reduce((text, middleware) => {
			return middleware(text)
		}, text) || text
		
		fs.writeFileSync(`${output}/${file}`, text)
	})
}

const getFolderStructure = (folder: string) => {
	let files = [] as Array<string>;
	const absoluteFolder = path.join(__dirname, folder)

	if (fs.lstatSync(absoluteFolder).isDirectory()) {
		fs.readdirSync(absoluteFolder).forEach((itemName: string) => {
			const itemPath = `${folder}/${itemName}`;
			if (fs.lstatSync(path.join(__dirname, itemPath)).isDirectory()) {
				files = [...files, ...getFolderStructure(itemPath).map(subItemName => `${itemName}/${subItemName}`)]
			} else {
				files.push(itemName)
			}
		})
	}

	return files;
}

const getPackages = (folder: string) => {
	let packages = [] as Array<PackageConfigType>
	const absoluteFolder = path.join(__dirname, folder)

	if (fs.lstatSync(absoluteFolder).isDirectory()) {
		fs.readdirSync(absoluteFolder).forEach((itemName: string) => {
			const itemPath = `${folder}/${itemName}`;
			const absoluteItemPath = path.join(__dirname, itemPath)
			if (fs.lstatSync(absoluteItemPath).isDirectory()) {
				fs.readdirSync(absoluteItemPath).forEach((subItemName: string) => {
					if (subItemName == 'config.json') {
						let config = JSON.parse(fs.readFileSync(`${absoluteItemPath}/${subItemName}`, 'utf8'))
						packages.push({
							path: itemPath,
							folder: itemName,
							...config
						})
					}
				})
			}
		})
	}

	return packages;
}

const installPackages = (packages: Array<PackageConfigType>, output: string, vendor: string, module: string) => {
	let includes = [] as Array<string>
	let replacements = [] as Array<string>

	packages.forEach(packageItem => {
		copyFolderSync(
			`${packageItem.path}/files`,
			output,
			[
				replaceModuleNameMiddleware(vendor, module),
			]
		);
		
		if (packageItem.include) {
			for (const key in packageItem.include) {
				if (Object.prototype.hasOwnProperty.call(packageItem.include, key)) {
					const includePath = packageItem.include[key]
					let text = fs.readFileSync(path.join(__dirname, `${packageItem.path}/${includePath}`), 'utf8')

					includes.push(key)
					replacements.push(text)
				}
			}
		}
	})

	return [includes, replacements]
}