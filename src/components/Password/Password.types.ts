import { type InputProps } from '#/components/ui/input'

export type PasswordVariant = 'current-password' | 'new-password'

export interface PasswordValidations {
	minLenght: boolean
	number: boolean
	mayusLetter: boolean
	specialCharacter: boolean
}

export interface PasswordProps extends InputProps {
	variant: PasswordVariant
}

export interface PasswordContextTypes {
	variant: PasswordVariant
	shown: boolean
	toggleShown: () => void
	validations: PasswordValidations
}
