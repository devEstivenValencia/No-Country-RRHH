import { AnySchema, safeParse } from 'valibot'

type Schema = AnySchema | any
type Values = any

export function schemaValidator(schema: Schema, values: Values) {
	return safeParse(schema, values)
}

export function schemaIsValid(schema: Schema, values: Values) {
	return schemaValidator(schema, values).success
}
