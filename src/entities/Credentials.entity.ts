import { emailSchema } from '#/schemas/email.schema'
import { object } from 'valibot'

import { Password, passwordSchema } from './Password'

export interface Credentials {
	email: string
	password: Password
}

export const credentialsSchema = object({
	email: emailSchema,
	password: passwordSchema
})

export const credentialsDefaultValues: Credentials = {
	email: '',
	password: ''
}
