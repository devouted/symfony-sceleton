import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/axios";
import ConfirmModal from "../components/ConfirmModal";

export default function Users() {
	const [users, setUsers] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);
	const [success, setSuccess] = useState(null);
	const [deleteModal, setDeleteModal] = useState({ isOpen: false, userId: null });
	const navigate = useNavigate();

	useEffect(() => {
		fetchUsers();
	}, []);

	const fetchUsers = async () => {
		try {
			setLoading(true);
			const response = await api.get("/admin/users");
			setUsers(response.data);
			setError(null);
		} catch (err) {
			setError(err.response?.data?.message || "Błąd podczas pobierania użytkowników");
		} finally {
			setLoading(false);
		}
	};

	const handleDeleteClick = (id) => {
		setDeleteModal({ isOpen: true, userId: id });
	};

	const handleDeleteConfirm = async () => {
		try {
			await api.delete(`/admin/users/${deleteModal.userId}`);
			setDeleteModal({ isOpen: false, userId: null });
			setSuccess("Użytkownik został usunięty");
			setTimeout(() => setSuccess(null), 3000);
			fetchUsers();
		} catch (err) {
			setError(err.response?.data?.message || "Błąd podczas usuwania użytkownika");
			setDeleteModal({ isOpen: false, userId: null });
		}
	};

	if (loading) {
		return (
			<div className="min-h-screen bg-gray-50 p-8 flex items-center justify-center">
				<div className="text-gray-600">Ładowanie...</div>
			</div>
		);
	}

	return (
		<div className="min-h-screen bg-gray-50 p-8">
			<div className="max-w-6xl mx-auto">
				<div className="flex justify-between items-center mb-6">
					<h1 className="text-3xl font-bold text-gray-900">Użytkownicy</h1>
					<button
						onClick={() => navigate("/users/new")}
						className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
					>
						Dodaj użytkownika
					</button>
				</div>

				{error && (
					<div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
						{error}
					</div>
				)}

				{success && (
					<div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
						{success}
					</div>
				)}

				<div className="bg-white shadow rounded-lg overflow-hidden">
					<table className="min-w-full divide-y divide-gray-200">
						<thead className="bg-gray-50">
							<tr>
								<th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
								<th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
								<th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
								<th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akcje</th>
							</tr>
						</thead>
						<tbody className="bg-white divide-y divide-gray-200">
							{users.map((user) => (
								<tr key={user.id}>
									<td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{user.id}</td>
									<td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{user.email}</td>
									<td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
										{user.roles.join(", ")}
									</td>
									<td className="px-6 py-4 whitespace-nowrap text-right text-sm">
										<button
											onClick={() => navigate(`/users/${user.id}/edit`)}
											className="text-blue-600 hover:text-blue-900 mr-4"
										>
											Edytuj
										</button>
										<button
											onClick={() => handleDeleteClick(user.id)}
											className="text-red-600 hover:text-red-900"
										>
											Usuń
										</button>
									</td>
								</tr>
							))}
						</tbody>
					</table>
				</div>
			</div>

			<ConfirmModal
				isOpen={deleteModal.isOpen}
				onClose={() => setDeleteModal({ isOpen: false, userId: null })}
				onConfirm={handleDeleteConfirm}
				title="Usuń użytkownika"
				message="Czy na pewno chcesz usunąć tego użytkownika?"
			/>
		</div>
	);
}