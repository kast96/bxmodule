export type PackageConfigType = {
  path: string
	folder: string
  name: string
  include?: {
    [key: string]: string
  }
}