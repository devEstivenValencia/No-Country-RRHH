'use client'

import { Typography } from "#/components";
import { APPROUTES } from "#/config/APP.routes";
import Image from "next/image";
import Link from "next/link";

export default function Home() {
	return (
		<section className="bg-primary-50 min-h-screen h-full">
			<nav className="flex justify-between bg-[#FFFFFF] md:h-20 h-24 items-center fixed top-0 left-0 right-0 shadow-md">
				<Link href={APPROUTES.HOME}>
                    <Image
			            src='/images/purple-logo-image.png'
			            alt='Logo'
			            width={50}
			            height={50}
			            className='m-3 ml-10'
			        />
                </Link>
				<ul className="flex flex-row text-right gap-3 mr-10">
					<li><Link href={APPROUTES.LOGIN} className="md:bg-primary-500 md:text-neutro-50 text-primary-500 md:border-none border border-[#FFFFFF] border-t-primary-500 md:rounded-3xl px-3 p-2 hover:font-semibold md:hover:font-normal md:hover:bg-primary-400">Iniciar sesión</Link></li>
					<li><Link href={APPROUTES.REGISTER} className="border md:border-primary-500 border-[#FFFFFF] border-b-primary-500 md:rounded-3xl px-3 p-2 text-neutro-800 hover:font-semibold md:hover:font-normal md:hover:text-primary-500">Registrarse</Link></li>
				</ul>
			</nav>
			<article className="pt-24">
				<section className="flex md:flex-row flex-col justify-center items-center md:mx-10 md:m-10">
					<div className="md:m-10 m-5 flex flex-col gap-5">
						<Typography as="h3" className="text-primary-500 font-bold">Un software de gestión de recursos humanos, diseñado para satisfacer las necesidades de empresas que demandan alto rendimiento</Typography>
						<Typography as="p" className="text-neutro-800 font-semibold">El software de gestión de recursos humanos simplificado ofrece una solución eficiente y accesible para empresas que buscan optimizar sus procesos de gestión de personal</Typography>
						<Link href={APPROUTES.REGISTER} className="bg-primary-500 text-neutro-50 rounded-3xl px-3 p-2 w-36 mb-10 hover:bg-primary-400">Comenzar ahora</Link>
					</div>
					<Image
			            src='/images/dashboard-image.png'
			            alt='Logo'
			            width={824}
			            height={420}
			            className='md:m-10 md:ml-5 rounded-3xl shadow-md'
			        />
				</section>
				<section className="flex md:flex-row flex-col justify-center items-center md:mx-20">
					<Image
			            src='/images/woman-image.png'
			            alt='Logo'
			            width={524}
			            height={120}
			            className='md:m-10 md:ml-5 mt-10 md:mt-0'
			        />
					<div className="flex flex-col gap-8 m-5 mt-0">
						<Typography as="p" className="bg-[#FFFFFF] p-4 rounded-3xl text-primary-500 font-semibold md:text-xl shadow-md">Ofrecemos soluciones integrales para la gestión de recursos humanos, adaptadas a empresas de todos los tamaños. Simplifica tus procesos de reclutamiento, selección, capacitación y gestión del personal con nuestra plataforma intuitiva y fácil de usar.</Typography>
						<Typography as="p" className="bg-[#FFFFFF] p-4 rounded-3xl text-primary-500 font-semibold md:text-xl shadow-md">Nuestra plataforma garantiza la seguridad de tus datos con encriptación de extremo a extremo, brindándote tranquilidad y protección para toda tu información sensible. Simplifica tus procesos de reclutamiento, selección, capacitación y gestión del personal con nuestra plataforma intuitiva y altamente segura.</Typography>
					</div>
				</section>
				<section className="text-center mt-10 m-5">
					<Typography as="h3" className="text-primary-500 font-bold">¿Por qué elegir nuestra plataforma de gestión de recursos humanos?</Typography>
					<Typography as="p" className="text-neutro-800 font-semibold mt-3">Existen varias razones para elegir nuestra plataforma por encima de otras opciones</Typography>
					<div className="flex flex-col md:flex-row md:mx-20 gap-10 my-10">
						<div className="bg-[#FFFFFF] p-4 rounded-3xl font-semibold shadow-md">
							<Typography as="h5" className=" text-primary-500 mb-3">Nos adaptamos a tu crecimiento</Typography>
							<Typography as="p" className=" text-neutro-800">Constantemente actualizamos nuestra plataforma con nuevas funcionalidades cada mes. Valoramos las opiniones de nuestros clientes y trabajamos para ofrecer las mejores herramientas de recursos humanos.</Typography>
						</div>
						<div className="bg-[#FFFFFF] p-4 rounded-3xl font-semibold shadow-md">
							<Typography as="h5" className=" text-primary-500 mb-3">Tu seguridad es nuestra prioridad</Typography>
							<Typography as="p" className=" text-neutro-800">En nuestra plataforma, la seguridad de tus datos es nuestra máxima prioridad. Utilizamos una encriptación de extremo a extremo para garantizar que toda tu información esté completamente protegida en todo momento. Puedes confiar en que tus datos estarán seguros y privados, permitiéndote gestionar tus recursos humanos con total tranquilidad y confianza.</Typography>
						</div>
						<div className="bg-[#FFFFFF] p-4 rounded-3xl font-semibold shadow-md">
							<Typography as="h5" className=" text-primary-500 mb-3">Optimizando tu experiencia</Typography>
							<Typography as="p" className=" text-neutro-800">No solo nos enfocamos en la seguridad, sino también en mejorar continuamente tu experiencia. Nuestra plataforma está diseñada para ser intuitiva y fácil de usar, lo que te permite maximizar tu eficiencia en la gestión de recursos humanos.</Typography>
						</div>
					</div>
				</section>
				<footer className="bg-primary-500 text-neutro-50 flex flex-col justify-center items-center h-28 mt-36">
					<Typography as="p">Condiciones y políticas de privacidad</Typography>
					<Typography as="p">© 2024 RRHH c17-118-t-php</Typography>
				</footer>
			</article>
		</section>
	)
}
