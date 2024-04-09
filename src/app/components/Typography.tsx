import React, { ElementType, HTMLAttributes } from 'react'

interface TypographyComponentProps<T extends ElementType = 'span'> extends HTMLAttributes<HTMLElement> {
	as?: T | ElementType
}

const TypographyComponent = <T extends ElementType = 'span'>({
	as: Tag = 'span',
	className,
	...props
}: TypographyComponentProps<T>) => {
	const defaultFontSizes: Record<string, string> = {
		h1: 'text-5xl',
		h2: 'text-4xl',
		h3: 'text-3xl',
		h4: 'text-2xl',
		h5: 'text-xl',
		h6: 'text-lg',
		p: 'text-base',
		span: 'text-base'
	}

	const defaultFontSize = defaultFontSizes[typeof Tag === 'string' ? Tag.toLowerCase() : ''] || ''

	const combinedClassName = `${defaultFontSize} ${className || ''}`

	return <Tag {...props} className={combinedClassName} />
}

export default TypographyComponent
