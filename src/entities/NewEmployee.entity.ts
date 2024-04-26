import { emailSchema } from '#/schemas/email.schema'
import { phoneSchema } from '#/schemas/phone.schema'
import * as v from 'valibot'

import { credentialsSchema } from './Credentials.entity'
import { Password } from './Password'

export interface NewEmployee {
	employee: {
		name: string
		dni: string
		location: {
			country: string
			province: string
			city: string
			address: string
		}
		contact: {
			email: string
			phone: string
		}
		credentials: {
			email: string
			password: Password
		}
		admissionDate: string
		role: string
	}
}

export const newEmployeeSchema = v.object({
	employee: v.object({
		name: v.string(),
		dni: v.string(),
		location: v.object({
			country: v.string(),
			province: v.string(),
			city: v.string(),
			address: v.string()
		}),
		contact: v.object({
			email: emailSchema,
			phone: phoneSchema
		}),
		credentials: credentialsSchema,
		admissionDate: v.string(),
		role: v.string()
	})
}) satisfies v.BaseSchema<NewEmployee>
