import { useParams } from "react-router-dom";

export default function UserEdit() {
	const { id } = useParams();
	
	return (
		<div className="min-h-screen bg-gray-50 p-8">
			<h1 className="text-3xl font-bold text-gray-900 mb-6">Edycja użytkownika #{id}</h1>
			<p className="text-gray-600">Formularz edycji użytkownika</p>
		</div>
	);
}