import { useState } from "react";
import { useAuth } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";
import { Button, Input, Card } from "../components/ui";

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
		<div className="min-h-screen flex items-center justify-center bg-base-200 py-12 px-4">
			<Card className="w-full max-w-md">
				<h2 className="text-center text-3xl font-bold mb-6">
					Zaloguj się do konta
				</h2>
				
				<form onSubmit={handleSubmit} className="space-y-4">
					<Input
						type="email"
						label="Email"
						placeholder="Adres email"
						value={email}
						onChange={(e) => setEmail(e.target.value)}
						error={errors.email}
					/>
					
					<Input
						type="password"
						label="Hasło"
						placeholder="Hasło"
						value={password}
						onChange={(e) => setPassword(e.target.value)}
						error={errors.password}
					/>

					{errors.api && (
						<div className="alert alert-error">
							<span>{errors.api}</span>
						</div>
					)}

					<Button
						type="submit"
						variant="primary"
						disabled={loading}
						className="w-full"
					>
						{loading ? "Logowanie..." : "Zaloguj się"}
					</Button>
				</form>
			</Card>
		</div>
	);
}