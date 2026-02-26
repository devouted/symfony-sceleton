import { useState } from "react";
import { useAuth } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";

export default function Login() {
	const [email, setEmail] = useState("");
	const [password, setPassword] = useState("");
	const [errors, setErrors] = useState({});
	const [loading, setLoading] = useState(false);
	const { login } = useAuth();
	const navigate = useNavigate();

	const validateForm = () => {
		const newErrors = {};
		
		if (!email) {
			newErrors.email = "Email jest wymagany";
		} else if (!/\S+@\S+\.\S+/.test(email)) {
			newErrors.email = "Nieprawidłowy format email";
		}
		
		if (!password) {
			newErrors.password = "Hasło jest wymagane";
		} else if (password.length < 6) {
			newErrors.password = "Hasło musi mieć minimum 6 znaków";
		}
		
		return newErrors;
	};

	const handleSubmit = async (e) => {
		e.preventDefault();
		
		const formErrors = validateForm();
		if (Object.keys(formErrors).length > 0) {
			setErrors(formErrors);
			return;
		}
		
		setLoading(true);
		setErrors({});
		
		try {
			await login(email, password);
			navigate("/dashboard");
		} catch (error) {
			setErrors({ 
				api: error.response?.data?.message || "Błąd logowania" 
			});
		} finally {
			setLoading(false);
		}
	};

	return (
		<div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
			<div className="max-w-md w-full space-y-8">
				<div>
					<h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
						Zaloguj się do konta
					</h2>
				</div>
				<form className="mt-8 space-y-6" onSubmit={handleSubmit}>
					<div className="rounded-md shadow-sm -space-y-px">
						<div>
							<label htmlFor="email" className="sr-only">
								Email
							</label>
							<input
								id="email"
								name="email"
								type="email"
								value={email}
								onChange={(e) => setEmail(e.target.value)}
								className="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
								placeholder="Adres email"
							/>
							{errors.email && (
								<p className="mt-1 text-sm text-red-600">{errors.email}</p>
							)}
						</div>
						<div>
							<label htmlFor="password" className="sr-only">
								Hasło
							</label>
							<input
								id="password"
								name="password"
								type="password"
								value={password}
								onChange={(e) => setPassword(e.target.value)}
								className="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
								placeholder="Hasło"
							/>
							{errors.password && (
								<p className="mt-1 text-sm text-red-600">{errors.password}</p>
							)}
						</div>
					</div>

					{errors.api && (
						<div className="rounded-md bg-red-50 p-4">
							<p className="text-sm text-red-800">{errors.api}</p>
						</div>
					)}

					<div>
						<button
							type="submit"
							disabled={loading}
							className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
						>
							{loading ? "Logowanie..." : "Zaloguj się"}
						</button>
					</div>
				</form>
			</div>
		</div>
	);
}