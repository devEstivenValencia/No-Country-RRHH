export interface Employee {
	id: string
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
	admissionDate: string
	finishDate: string
	state: string
	role: string
}
