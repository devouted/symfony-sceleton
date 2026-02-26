import { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/axios";

const AVAILABLE_ROLES = [
	{ value: "ROLE_USER", label: "User" },
	{ value: "ROLE_ADMIN", label: "Admin" },
];

export default function UserNew() {
	const [formData, setFormData] = useState({ email: "", password: "", roles: ["ROLE_USER"] });
	const [error, setError] = useState(null);
	const [validationErrors, setValidationErrors] = useState({});
	const [loading, setLoading] = useState(false);
	const navigate = useNavigate();

	const validateForm = () => {
		const errors = {};
		
		if (!formData.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
			errors.email = "Nieprawidłowy format email";
		}
		
		if (formData.password.length < 6) {
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
			await api.post("/admin/users", formData);
			navigate("/users");
		} catch (err) {
			setError(err.response?.data?.message || "Błąd podczas tworzenia użytkownika");
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

	return (
		<div className="min-h-screen bg-gray-50 p-8">
			<div className="max-w-2xl mx-auto">
				<h1 className="text-3xl font-bold text-gray-900 mb-6">Nowy użytkownik</h1>

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
						<label className="block text-gray-700 mb-2">Hasło</label>
						<input
							type="password"
							value={formData.password}
							onChange={(e) => setFormData({ ...formData, password: e.target.value })}
							className={`w-full border rounded px-3 py-2 ${validationErrors.password ? 'border-red-500' : 'border-gray-300'}`}
							required
						/>
						{validationErrors.password && (
							<p className="text-red-500 text-sm mt-1">{validationErrors.password}</p>
						)}
					</div>

					<div className="mb-6">
						<label className="block text-gray-700 mb-2">Role</label>
						<div className="space-y-2">
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