import { emailSchema } from '#/schemas/email.schema'
import { phoneSchema } from '#/schemas/phone.schema'
import * as v from 'valibot'

import { credentialsSchema } from './Credentials.entity'
import { Password } from './Password'

export interface NewEnterprise {
	companyName: string
	credentials: {
		email: string
		password: Password
	}
	contact: {
		email: string
		phone: string
	}
}

export const newEnterpriseSchema = v.object({
	companyName: v.string(),
	credentials: credentialsSchema,
	contact: v.object({
		email: emailSchema,
		phone: phoneSchema
	})
}) satisfies v.BaseSchema<NewEnterprise>
