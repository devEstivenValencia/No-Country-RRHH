import { cn } from '#/lib/utils'
import { HTMLAttributes } from 'react'

interface Props extends HTMLAttributes<HTMLElement> {
	as: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6' | 'p' | 'span'
}

function Typography({ as: Tag, ...props }: Props) {
	const defaultFontSizes = {
		h1: 'text-5xl',
		h2: 'text-4xl',
		h3: 'text-3xl',
		h4: 'text-2xl',
		h5: 'text-xl',
		h6: 'text-lg',
		p: 'text-base',
		span: 'text-base'
	}

	return <Tag {...props} className={cn(defaultFontSizes[Tag], props.className)} />
}

export default Typography
