import Icon from '#/app/components/Icono'
import '../styles/icons.css'

import TypographyComponent from './components/Typography'

export default function Home() {
	return (
		<div>
			<h1>Landing Page</h1>
			<Icon name='cat' size='80px' />
			<TypographyComponent as='h2'>Testing Component as H6</TypographyComponent>
			<TypographyComponent as='h5'>Testing Component as H2</TypographyComponent>
			<TypographyComponent as='h6'>Testing Component as H6</TypographyComponent>
		</div>
	)
}
