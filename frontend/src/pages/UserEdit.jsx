import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../api/axios";

const AVAILABLE_ROLES = [
	{ value: "ROLE_USER", label: "User" },
	{ value: "ROLE_ADMIN", label: "Admin" },
];

export default function UserEdit() {
	const { id } = useParams();
	const [formData, setFormData] = useState({ email: "", password: "", roles: [] });
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);
	const [validationErrors, setValidationErrors] = useState({});
	const navigate = useNavigate();

	useEffect(() => {
		fetchUser();
	}, [id]);

	const fetchUser = async () => {
		try {
			const response = await api.get(`/admin/users/${id}`);
			setFormData({ email: response.data.email, password: "", roles: response.data.roles });
			setError(null);
		} catch (err) {
			setError(err.response?.data?.message || "Błąd podczas pobierania użytkownika");
		} finally {
			setLoading(false);
		}
	};

	const validateForm = () => {
		const errors = {};
		
		if (!formData.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
			errors.email = "Nieprawidłowy format email";
		}
		
		if (formData.password && formData.password.length < 6) {
			errors.password = "Hasło musi mieć minimum 6 znaków";
		}
		
		setValidationErrors(errors);
		return Object.keys(errors).length === 0;
	};

	const handleSubmit = async (e) => {
		e.preventDefault();
		
		if (!validateForm()) return;
		
		setLoading(true);
		try {
			const payload = { email: formData.email };
			if (formData.password) payload.password = formData.password;
			await api.put(`/admin/users/${id}`, payload);
			navigate("/users");
		} catch (err) {
			setError(err.response?.data?.message || "Błąd podczas aktualizacji użytkownika");
			setLoading(false);
		}
	};

	const handleRolesSubmit = async () => {
		setLoading(true);
		try {
			await api.post(`/admin/users/${id}/roles`, { roles: formData.roles });
			setError(null);
			alert("Role zostały zaktualizowane");
		} catch (err) {
			setError(err.response?.data?.message || "Błąd podczas aktualizacji ról");
		} finally {
			setLoading(false);
		}
	};

	const toggleRole = (role) => {
		setFormData(prev => ({
			...prev,
			roles: prev.roles.includes(role)
				? prev.roles.filter(r => r !== role)
				: [...prev.roles, role]
		}));
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
			<div className="max-w-2xl mx-auto">
				<h1 className="text-3xl font-bold text-gray-900 mb-6">Edycja użytkownika #{id}</h1>

				{error && (
					<div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
						{error}
					</div>
				)}

				<form onSubmit={handleSubmit} className="bg-white shadow rounded-lg p-6">
					<div className="mb-4">
						<label className="block text-gray-700 mb-2">Email</label>
						<input
							type="email"
							value={formData.email}
							onChange={(e) => setFormData({ ...formData, email: e.target.value })}
							className={`w-full border rounded px-3 py-2 ${validationErrors.email ? 'border-red-500' : 'border-gray-300'}`}
							required
						/>
						{validationErrors.email && (
							<p className="text-red-500 text-sm mt-1">{validationErrors.email}</p>
						)}
					</div>

					<div className="mb-4">
						<label className="block text-gray-700 mb-2">Nowe hasło (opcjonalne)</label>
						<input
							type="password"
							value={formData.password}
							onChange={(e) => setFormData({ ...formData, password: e.target.value })}
							className={`w-full border rounded px-3 py-2 ${validationErrors.password ? 'border-red-500' : 'border-gray-300'}`}
						/>
						{validationErrors.password && (
							<p className="text-red-500 text-sm mt-1">{validationErrors.password}</p>
						)}
					</div>

					<div className="mb-6">
						<label className="block text-gray-700 mb-2">Role</label>
						<div className="space-y-2 mb-3">
							{AVAILABLE_ROLES.map(role => (
								<label key={role.value} className="flex items-center">
									<input
										type="checkbox"
										checked={formData.roles.includes(role.value)}
										onChange={() => toggleRole(role.value)}
										className="mr-2"
									/>
									<span className="text-gray-700">{role.label}</span>
								</label>
							))}
						</div>
						<button
							type="button"
							onClick={handleRolesSubmit}
							disabled={loading}
							className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:bg-gray-400"
						>
							Zapisz role
						</button>
					</div>

					<div className="flex gap-4">
						<button
							type="submit"
							disabled={loading}
							className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400"
						>
							{loading ? "Zapisywanie..." : "Zapisz"}
						</button>
						<button
							type="button"
							onClick={() => navigate("/users")}
							className="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
						>
							Anuluj
						</button>
					</div>
				</form>
			</div>
		</div>
	);
}