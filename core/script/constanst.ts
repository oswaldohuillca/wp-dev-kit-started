import { dirname } from 'path'

export const __dirname = dirname(dirname(dirname(__filename)))
// const __dirname = fileURLToPath(new URL('.', import.meta.url))
