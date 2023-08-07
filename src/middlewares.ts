import { replaceModuleName, replaceAllArray } from "./utils"

export const replaceModuleNameMiddleware = (vendor: string, module: string) => (text: string) => {
	return replaceModuleName(text, vendor, module)
}

export const replaceDateMiddleware = (text: string) => {
	const date = new Date()
	const dateString = `${date.getFullYear()}-${("0"+(date.getMonth()+1)).slice(-2)}-${('0' + date.getDate()).slice(-2)} ${("0" + date.getHours()).slice(-2)}:${("0" + date.getMinutes()).slice(-2)}:${("0" + date.getSeconds()).slice(-2)}`
	return text.replaceAll('{date}', dateString)
}

export const replaceMiddleware = (patterns: Array<string>, replacements: Array<string>) => (text: string) => {
	return replaceAllArray(text, patterns, replacements)
}

export const replaceIncludesMiddleware = (patterns: Array<string>, replacements: Array<string>) => (text: string) => {
	return replaceAllArray(text, patterns.map(pattern => `{include:${pattern}}`), replacements.map((replacement, key) => `${replacement}\n{include:${patterns[key]}}`))
}

export const clearIncludesMiddleware = (text: string) => {
	return text.replaceAll(/[\n\t]*{include:[^}]*}/g, '')
}