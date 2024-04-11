function Icon({ name }: { name: string }) {
	if (name.includes('fi')) throw new Error('Ingrese unicamente la referencia del icono ')

	return <i className={`fi fi-sr-${name}`}></i>
}

export default Icon
