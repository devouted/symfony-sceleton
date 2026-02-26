export default function ConfirmModal({ isOpen, onClose, onConfirm, title, message }) {
	if (!isOpen) return null;

	return (
		<div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
			<div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
				<h2 className="text-xl font-bold text-gray-900 mb-4">{title}</h2>
				<p className="text-gray-600 mb-6">{message}</p>
				<div className="flex gap-4 justify-end">
					<button
						onClick={onClose}
						className="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
					>
						Anuluj
					</button>
					<button
						onClick={onConfirm}
						className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
					>
						Usuń
					</button>
				</div>
			</div>
		</div>
	);
}
