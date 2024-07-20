'use client'

import { Button, Icon, Typography } from "#/components";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "#/components/ui/table";
import { useEffect, useState } from "react";
import { Card, CardHeader, CardTitle } from "#/components/ui/card";
import { useRouter } from "next/navigation";
import { APPROUTES } from "#/config/APP.routes";/* 
import useBroadcastNewVacation from "#/hooks/useBroadcastNewVacation"; */
import { VacationEmployee } from "#/entities/VacationEmployee.entity";
import { getVacationEmployeesService } from "#/services";
import useSecurity from "#/hooks/useSecurity";/* 
import { sleep } from "#/lib/utils"; *//* 
import Pusher from 'pusher-js' *//* 
import * as PusherTypes from 'pusher-js' */
import useBroadcastNewVacation from "#/hooks/useBroadcastNewVacation";

export default function Dashboard() {
	const [vacation] = useBroadcastNewVacation()
	const { error, keypairCreated, keypair, publicPemKey } = useSecurity();
    const [vacations, setVacations] = useState<VacationEmployee[]>([])
    const [clickAccions, setClickAccions] = useState(true);/* 
    const [loading, setLoading] = useState(true); */
    const [user,setUser] = useState<any>();
    const [userLoading,setUserLoading] = useState(false);
    const handleClickAccions = () => setClickAccions(!clickAccions);
	const router = useRouter()/* 
	const [vacation, setVacation] = useState<string>('') */
/* 
	useEffect(() => {
	}, []) */

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

    useEffect(() => {
        setVacations([])
        fetchEmployees()
    }, [keypairCreated,vacation]);

    async function fetchEmployees(){
        console.log('cargando empleados 2')
        if(keypairCreated && publicPemKey){
            console.log('await')
            await getVacationEmployeesService(keypair, publicPemKey )
                .then((data) => setVacations(data.employees))
                .catch(({ message }) => console.log(message))/* 
                .finally(() => setLoading(false)) */
        }else{/* 
            setErrorFetch(error) */
            console.log(error)
        }
    }

    // Datos simulados
    const totalEmployees = 32;
    const activeEmployees = 29;
    const inactiveEmployees = 3;

    return (
        <section className="bg-primary-50 min-h-screen h-full w-full flex flex-col pt-2">
            <nav className="md:bg-[#FFFFFF] md:h-20 md:rounded-3xl md:m-8 md:shadow-md md:justify-between md:px-10 flex justify-center mt-16 items-center">
                <Typography as='h3' className="text-primary-500 font-semibold ml-3">Inicio</Typography>
                <div className="flex items-center">
                    <Button className=" hover:text-primary-500 text-neutro-800 rounded-3xl hidden md:block">
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
            <div className="flex justify-center text-center mt-8 mx-8">
                <Card className="flex items-center justify-center shadow-md rounded-3xl mr-4 bg-[#FFFFFF] border border-neutro-100 w-72 md:h-52">
                    <CardHeader>
                        <Typography as="p" className="text-3xl font-semibold text-blue-500 text-center">{totalEmployees}</Typography>
                        <CardTitle className="text-xs">Total de Empleados</CardTitle>
                    </CardHeader>
                </Card>
                <Card className="flex items-center justify-center bg-[#FFFFFF] shadow-md rounded-3xl mr-4 border border-neutro-100 w-72 md:h-52">
                    <CardHeader>
                        <Typography as="p" className="text-3xl font-semibold text-green-500 text-center">{activeEmployees}</Typography>
                        <CardTitle className="text-xs">Empleados activos</CardTitle>
                    </CardHeader>
                </Card>
                <Card className="flex items-center justify-center bg-[#FFFFFF] shadow-md rounded-3xl border border-neutro-100 w-72 md:h-52">
                    <CardHeader>
                        <Typography as="p" className="text-3xl font-semibold text-red-500 text-center">{inactiveEmployees}</Typography>
                        <CardTitle className="text-xs">Empleados inactivos</CardTitle>
                    </CardHeader>
                </Card>
            </div>
            <div className="bg-[#FFFFFF] rounded-3xl md:mt-14 m-8 shadow-md items-center md:px-12 p-12">
				<Typography as="h3" className="text-primary-500 text-3xl font-semibold mb-4">Solicitudes de Vacaciones</Typography>
                <Table className="mt-4 border border-neutro-300 rounded-lg md:bg-[#FFFFFF]">
                    <TableHeader>
                        <TableRow className="border-b-neutro-300 font-bold text-neutro-700">
                            <TableHead>Nombre</TableHead>
                            <TableHead>Correo Electrónico</TableHead>
                            <TableHead>Rol</TableHead>
                            <TableHead>fechas</TableHead>
                            <TableHead className="text-right">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        { vacations.map(( e: VacationEmployee ) => {
                            return (
                                <TableRow key={e.id} className="border-b-neutro-300 h-12">
                                <TableCell>{e.name}</TableCell>
                                <TableCell>{e.email}</TableCell>
                                <TableCell>{e.role}</TableCell>
                                <TableCell>{e.dates}</TableCell>
                                <TableCell onClick={handleClickAccions} className="flex justify-end">
                                    {clickAccions ? <Button className="w-16"><Icon name="menu-dots-vertical"></Icon></Button> :
                                        <div className="rounded-md shadow-md flex w-16">
                                            <Button className="p-1 text-green-300 hover:text-neutro-50 hover:bg-green-500">
                                                <Icon name="check"></Icon>
                                            </Button>
                                            <Button className="p-1 text-red-300 hover:text-neutro-50 hover:bg-red-500">
                                                <Icon name="cross-small"></Icon>
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
