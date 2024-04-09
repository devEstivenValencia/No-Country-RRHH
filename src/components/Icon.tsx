function Icon({ name, size }: { name: string; size: string }) {
	return <i className={`fi fi-sr-${name}`} style={{ fontSize: `${size}` }}></i>
}

export default Icon
