import { regex, safeParse, string } from 'valibot'

export type Password = string

const PasswordSchema = string([
	regex(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/, 'No cumple las validaciones')
])

export function validatePassword(password: Password) {
	return safeParse(PasswordSchema, password).success
}
