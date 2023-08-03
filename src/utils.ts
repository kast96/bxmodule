export const replaceAllArray = (text: string, patterns: Array<string>, replacements: Array<string>) => {
  patterns.map((pattern, key) => {
    text = text.replaceAll(pattern, replacements[key])
  })
  
  return text
}

export const replaceModuleName = (text: string, vendor: string, module: string) => {
  
  return replaceAllArray(
    text,
    [
      ...wordToCaseVariants('vendor').map(item => '{'+item+'}'),
      ...wordToCaseVariants('module').map(item => '{'+item+'}')
    ],
    [
      ...wordToCaseVariants(vendor),
      ...wordToCaseVariants(module)
    ]
  )
}

export const wordToCaseVariants = (word: string) => {
  return [
    word.toLowerCase(),
    toUppercaseFirstChart(word),
    word.toUpperCase()
  ]
}

export const toUppercaseFirstChart = (word: string) => {
  const firstLetter = word.charAt(0)
  const firstLetterCap = firstLetter.toUpperCase()
  const remainingLetters = word.slice(1)
  return firstLetterCap + remainingLetters
}

export const answer = async (readline: any, question: string) => {
  return await new Promise<string>(resolve => {
    readline.question(question, resolve)
  })
}