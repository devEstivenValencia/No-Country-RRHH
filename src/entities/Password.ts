import { regex, string } from 'valibot'

export type Password = string

export const passwordSchema = string([
	regex(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/, 'No cumple las validaciones')
])
