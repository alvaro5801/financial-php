import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react'; 

export default function Dashboard({ auth, kpis, transactions }) {
    
    
    const { post, processing } = useForm();

    
    const formatMoney = (value) => {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    };

    
    const handleSync = () => {
        
        post(route('transactions.sync'));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard Financeiro</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    
                    {/* --- CABEÇALHO COM BOTÃO DE SINCRONIZAR (NOVO) --- */}
                    <div className="flex justify-between items-center mb-6 px-2 sm:px-0">
                        <h2 className="text-xl font-semibold text-gray-800">Visão Geral</h2>
                        
                        <button 
                            onClick={handleSync}
                            disabled={processing} // Desabilita se estiver carregando
                            className={`
                                flex items-center px-4 py-2 bg-indigo-600 border border-transparent 
                                rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 
                                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                                transition ease-in-out duration-150
                                ${processing ? 'opacity-50 cursor-not-allowed' : ''}
                            `}
                        >
                            {processing ? (
                                <>
                                    <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sincronizando...
                                </>
                            ) : (
                                '🔄 Sincronizar Stripe'
                            )}
                        </button>
                    </div>

                    {/* --- KPIS (CARDS) --- */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {/* Card 1: Volume Total */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div className="text-gray-500 text-sm uppercase font-bold">Volume Total Processado</div>
                            <div className="text-3xl font-bold text-gray-900 mt-2">
                                {formatMoney(kpis.volume)}
                            </div>
                        </div>

                        {/* Card 2: Lucro da Plataforma */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                            <div className="text-gray-500 text-sm uppercase font-bold">Receita da Plataforma</div>
                            <div className="text-3xl font-bold text-green-600 mt-2">
                                {formatMoney(kpis.profit)}
                            </div>
                            <div className="text-xs text-gray-400 mt-1">Comissões acumuladas</div>
                        </div>
                    </div>

                    {/* --- TABELA DE TRANSAÇÕES --- */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h3 className="text-lg font-bold mb-4">Últimas Transações</h3>
                            
                            <div className="overflow-x-auto">
                                <table className="min-w-full text-left text-sm whitespace-nowrap">
                                    <thead className="uppercase tracking-wider border-b-2 border-gray-200 bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-4">ID Stripe</th>
                                            <th className="px-6 py-4">Valor</th>
                                            <th className="px-6 py-4">Comissão</th>
                                            <th className="px-6 py-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {transactions.map((tx) => (
                                            <tr key={tx.id} className="border-b hover:bg-gray-50">
                                                <td className="px-6 py-4 font-mono text-xs">{tx.stripe_id}</td>
                                                <td className="px-6 py-4 font-medium">{formatMoney(tx.amount)}</td>
                                                <td className="px-6 py-4 text-green-600">+{formatMoney(tx.platform_fee)}</td>
                                                <td className="px-6 py-4">
                                                    <span className={`px-2 py-1 rounded-full text-xs font-bold ${
                                                        tx.status === 'succeeded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                                    }`}>
                                                        {tx.status}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                        {transactions.length === 0 && (
                                            <tr>
                                                <td colSpan="4" className="px-6 py-4 text-center text-gray-500">
                                                    Nenhuma transação encontrada.
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
