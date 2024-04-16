import { emailSchema } from '#/schemas/email.schema'
import { phoneSchema } from '#/schemas/phone.schema'
import { object } from 'valibot'

export interface Contact {
	email: string
	phone: string
}

export const contactSchema = object({
	email: emailSchema,
	phone: phoneSchema
})

export const contactDefaultValues: Contact = {
	email: '',
	phone: ''
}
