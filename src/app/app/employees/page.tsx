'use client'

import { Button, Icon, Input, Typography } from "#/components";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "#/components/ui/table"
import { useEffect, useState } from "react"
import { EmployeeForm } from "#/components/Employees/EmployeeForm.component";
import useSecurity from "#/hooks/useSecurity";
import { Employee } from "#/entities/Employee.entity";
import { getEmployeesService } from "#/services/getEmployees.service";
import { deleteEmployeeService } from "#/services";
import { APPROUTES } from "#/config/APP.routes";
import { useRouter } from "next/navigation";
import { sleep } from "#/lib/utils";

export default function EmployeesPage () {
	const { error, keypairCreated, keypair, publicPemKey } = useSecurity();/* 
    const [loading, setLoading] = useState<boolean>(true) */
    const [employees, setEmployees] = useState<Employee[]>([])/* 
    const [errorFetch, setErrorFetch] = useState<string>('') */
    const [user,setUser] = useState<any>();
    const [userLoading,setUserLoading] = useState(false);
    const router = useRouter()

    const [open, setOpen] = useState<boolean>(false)

    const [clickAccions, setClickAccions] = useState(true)
    const handleClickAccions = () => setClickAccions(!clickAccions)
    
    useEffect(() => {
        fetchEmployees()
    }, [keypairCreated])
/* 
    useEffect(() => {
        if(error) setErrorFetch(error)
    }, [error]); */
    
    async function fetchEmployees(){
		// eslint-disable-next-line no-unmodified-loop-condition
		while(!error && !keypairCreated){
			await sleep(100)
		}
        if(keypairCreated && publicPemKey){
            await getEmployeesService(keypair, publicPemKey )
                .then((data) => setEmployees(data.employees))/* 
                .catch(({ message }) => setErrorFetch(message))
                .finally(() => setLoading(false)) */
        }else{/* 
            setErrorFetch(error) */
        }
    }

    function editEmployee(id: string){

    }
    async function deleteEmployee(id: string){/* 
        setLoading(true) */
        deleteEmployeeService(id)
        .then((data) => {
            setEmployees([])
            fetchEmployees()
        })/* 
        .catch(({ message }) => setErrorFetch(message))
        .finally(() => setLoading(false)) */
    }

    function reset(){
        setEmployees([])
        setOpen(false)
        fetchEmployees()
    }

    useEffect(()=>{
        const user = JSON.parse(localStorage.getItem('user') || '{}')
        console.log('user ', user)
        if (user?.type === 'company' && !user.verified)
            router.push(APPROUTES.ENTERPRISE)
        if(!userLoading){
            setUser(user)
            setUserLoading(true)
        }
    },[])

    return (
        <section className="bg-primary-50 min-h-screen h-full w-full flex flex-col pt-2">
            <nav className="md:bg-[#FFFFFF] md:h-20 md:rounded-3xl md:m-8 md:shadow-md md:justify-between md:px-10 flex justify-center mt-16 items-center">
                <Typography as='h3' className="text-primary-500 font-semibold ml-3">Empleados</Typography>
                <div className="flex items-center">
                    <Button className="text-neutro-800 hover:text-primary-500 rounded-3xl hidden md:block">
                        <Icon name="bell"></Icon>
                    </Button>
                    <Button className="relative bottom-16 ml-5 md:static">
                        <div className="bg-neutro-200 p-2 rounded-full mr-1 text-neutro-800">
                            <Icon name="user"></Icon>
                        </div>
                        <div className="text-left pl-2">
                            <Typography as='p' className="font-bold text-neutro-950">{user && user?.company_name}</Typography>
                            <Typography as='p' className="text-neutro-500 font-bold text-xs">Recursos Humanos</Typography>
                        </div>
                    </Button>
                </div>
            </nav>
            <div className="bg-[#FFFFFF] h-auto w-auto rounded-3xl md:mt-14 m-8 shadow-md items-center md:px-12 p-12">
                <div className="flex justify-between gap-3">
                    <Input type="text" placeholder="Buscar Empleado" className="md:w-96 h-10 gap-3 border-neutro-300"></Input>
                    <EmployeeForm reset={reset} open={open} setOpen={setOpen} />
                </div>
                <Table className="mt-4 border border-neutro-300">
                    <TableHeader>
                        <TableRow className="border-b-neutro-300 font-bold text-neutro-700">
                        <TableHead className="">Nombre</TableHead>
                        <TableHead>DNI</TableHead>
                        <TableHead>País</TableHead>
                        <TableHead>Correo electrónico</TableHead>
                        <TableHead>Fecha de ingreso</TableHead>
                        <TableHead>Fecha de salida</TableHead>
                        <TableHead>Rol</TableHead>
                        <TableHead className="text-right">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        { employees && employees.map((emp: Employee)=>{
                            return (
                                <TableRow className="border-b-neutro-300 h-12" key={emp.id}>
                                    <TableCell>{emp.name}</TableCell>
                                    <TableCell>{emp.dni}</TableCell>
                                    <TableCell>{emp.location.country}</TableCell>
                                    <TableCell>{emp.contact.email}</TableCell>
                                    <TableCell>{emp.admissionDate}</TableCell>
                                    <TableCell>{emp.finishDate}</TableCell>
                                    <TableCell>{emp.role}</TableCell>
                                    <TableCell onClick={handleClickAccions} className="flex justify-end">
                                        {clickAccions ? <Button className="w-16"><Icon name="menu-dots-vertical"></Icon></Button> :
                                        <div className="rounded-md shadow-md flex w-16">
                                            <Button onClick={ () => editEmployee(emp.id) } className="p-1 text-yellow-200 hover:text-yellow-500">
                                                <Icon name="edit"></Icon>
                                            </Button>
                                            <Button onClick={ () => deleteEmployee(emp.id)} className="p-1 text-red-300 hover:text-red-500">
                                                <Icon name="trash"></Icon>
                                            </Button>
                                        </div>
                                        }
                                    </TableCell>
                                </TableRow>
                            )
                        })}
                    </TableBody>
                </Table>
            </div>
        </section>
    )
}