import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { Car, Users, TrendingUp, Wrench, DollarSign, Calendar, Activity, BarChart3, X, ChevronRight, Loader2, RefreshCw, Crown, PieChart as PieChartIcon, LineChart as LineChartIcon } from "lucide-react";
import { LineChart, Line, BarChart, Bar, PieChart, Pie, Cell, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, AreaChart, Area, ReferenceLine, LabelList } from "recharts";
import api from "../services/api";
import { toast } from "sonner";
import dayjs from "dayjs";

const COLORS = ["#3b82f6","#10b981","#f59e0b","#ef4444","#8b5cf6","#06b6d4"];
const fmt = (n: any) => "₱" + Number(n||0).toLocaleString("en-PH",{minimumFractionDigits:2,maximumFractionDigits:2});

function Modal({title,color,onClose,children}:{title:string;color:string;onClose:()=>void;children:any}) {
  return (
    <div className="fixed inset-0 bg-black/60 z-50 flex items-end justify-center" onClick={onClose}>
      <div className="bg-white w-full max-h-[85vh] rounded-t-3xl overflow-hidden flex flex-col" onClick={e=>e.stopPropagation()}>
        <div className={`p-4 ${color} flex items-center justify-between flex-shrink-0`}>
          <span className="text-white font-bold text-base">{title}</span>
          <button onClick={onClose}><X className="w-5 h-5 text-white"/></button>
        </div>
        <div className="overflow-y-auto flex-1 p-4">{children}</div>
      </div>
    </div>
  );
}

export function Dashboard() {
  const navigate = useNavigate();
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [activeModal, setActiveModal] = useState<string|null>(null);
  const [days, setDays] = useState(7);
  const [error, setError] = useState<string|null>(null);

  useEffect(()=>{ load(); },[]);
  useEffect(()=>{ if(data) loadTrend(); },[days]);

  const load = async () => {
    setLoading(true); setError(null);
    try {
      const r = await api.get("/dashboard?days="+days);
      if(r.data.success) setData(r.data);
      else setError("Server returned error");
    } catch(e:any) {
      setError(e?.response?.data?.message || e?.message || "Network error");
    } finally { setLoading(false); }
  };

  const loadTrend = async () => {
    try {
      const r = await api.get("/dashboard?days="+days);
      if(r.data.success) setData((p:any)=>({...p, chartData: {...p.chartData, revenueTrend: r.data.chartData?.revenueTrend||[]}}));
    } catch{}
  };

  if(loading) return (
    <div className="flex flex-col items-center justify-center min-h-[60vh] gap-3">
      <Loader2 className="w-8 h-8 animate-spin text-blue-600"/>
      <p className="text-gray-500 text-sm italic font-medium tracking-tight">Accessing fleet data...</p>
    </div>
  );

  if(error) return (
    <div className="flex flex-col items-center justify-center min-h-[60vh] gap-4 p-6">
      <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
        <X className="w-8 h-8 text-red-500"/>
      </div>
      <p className="text-gray-700 font-bold text-center">Failed to load dashboard</p>
      <p className="text-red-500 text-sm text-center bg-red-50 p-3 rounded-xl border border-red-100">{error}</p>
      <button onClick={load} className="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">
        <RefreshCw className="w-4 h-4"/> Retry
      </button>
    </div>
  );

  const stats = data?.stats;
  const charts = data?.chartData;
  const insights = data?.insights;
  const modal = data?.modalData;

  const cards = [
    {id:"units",    label:"Total Units",       val:stats?.active_units??0,             sub:`${stats?.roi_achieved??0} ROI Achieved`, icon:Car,      c:"text-blue-500", bg:"bg-blue-50", bd:"border-blue-100", wave:"rgba(59, 130, 246, 0.05)"},
    {id:"boundary", label:"Boundary Revenue",  val:fmt(stats?.today_boundary),          sub:`${fmt(stats?.month_boundary)} this month`, icon:DollarSign, c:"text-emerald-500", bg:"bg-emerald-50", bd:"border-emerald-100", wave:"rgba(16, 185, 129, 0.05)"},
    {id:"income",   label:"Net Income (Kita)",  val:fmt(stats?.net_income),              sub:`${fmt(stats?.net_income_month)} this month`, icon:TrendingUp, c:"text-green-600", bg:"bg-green-50", bd:"border-green-200", wave:"rgba(34, 197, 94, 0.05)"},
    {id:"maintenance",label:"Units Under Mntnc",val:stats?.maintenance_units??0,         sub:"Ongoing maintenance",   icon:Wrench,    c:"text-orange-500", bg:"bg-orange-50", bd:"border-orange-100", wave:"rgba(249, 115, 22, 0.05)"},
    {id:"drivers",  label:"Active Drivers",    val:stats?.active_drivers??0,            sub:"Registered drivers",    icon:Users,     c:"text-indigo-500", bg:"bg-indigo-50", bd:"border-indigo-100", wave:"rgba(99, 102, 241, 0.05)"},
    {id:"expenses", label:"Total Expenses",    val:fmt(stats?.today_expenses),          sub:"Today total",           icon:Activity,  c:"text-rose-500", bg:"bg-rose-50", bd:"border-rose-100", wave:"rgba(244, 63, 94, 0.05)"},
    {id:"coding",   label:"Coding Units Today", val:stats?.coding_units??0,             sub:new Date().toLocaleDateString("en-PH",{weekday:"long"}), icon:Calendar, c:"text-violet-500", bg:"bg-violet-50", bd:"border-violet-100", wave:"rgba(139, 92, 246, 0.05)"},
  ];

  return (
    <div className="space-y-4 pb-20 p-2">
      {/* Header matching web dashboard precisely but more compact */}
      <div className="flex flex-col gap-0.5">
        <div className="flex items-center justify-between">
          <h1 className="text-xl font-black text-gray-900 tracking-tight">Euro Taxi System</h1>
          <button onClick={load} className="p-2 bg-gray-100 rounded-xl active:bg-gray-200 transition-colors">
            <RefreshCw className="w-4 h-4 text-gray-500"/>
          </button>
        </div>
        <div className="flex items-center gap-2">
           <div className="w-1 h-1 bg-green-500 rounded-full animate-pulse"></div>
           <p className="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Live • {new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}</p>
        </div>
      </div>

      {/* 1. STATS CARDS (Matching Web Design + User Arrangement) */}
      <div className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        {[
          { 
            id: 'units', 
            label: "Total Units", 
            val: stats?.active_units, 
            sub: `${stats?.roi_achieved} ROI Achieved`, 
            icon: Car, 
            bg: "bg-blue-50/50", 
            bd: "border-blue-100", 
            c: "text-blue-600",
            iconBg: "bg-blue-100/50"
          },
          { 
            id: 'boundary', 
            label: "Boundary Revenue", 
            val: fmt(stats?.today_boundary), 
            sub: "TODAY", 
            sub2: `${fmt(stats?.month_boundary)} THIS MONTH`,
            icon: DollarSign, 
            bg: "bg-emerald-50/50", 
            bd: "border-emerald-100", 
            c: "text-emerald-600",
            iconBg: "bg-emerald-100/50"
          },
          { 
            id: 'net', 
            label: "Net Income (Kita)", 
            val: fmt(stats?.net_income), 
            sub: "TODAY", 
            sub2: `${fmt(stats?.net_income_month)} THIS MONTH`,
            icon: TrendingUp, 
            bg: "bg-green-50/50", 
            bd: "border-green-100", 
            c: "text-green-600",
            iconBg: "bg-green-100/50"
          },
          { 
            id: 'mnt', 
            label: "Units Under Mntnc", 
            val: stats?.maintenance_units, 
            sub: "Ongoing Maintenance", 
            icon: Wrench, 
            bg: "bg-orange-50/50", 
            bd: "border-orange-100", 
            c: "text-orange-600",
            iconBg: "bg-orange-100/50"
          },
        ].map((s) => (
          <div key={s.id} onClick={()=>setActiveModal(s.id)}
            className={`group relative overflow-hidden rounded-[1.5rem] ${s.bg} border ${s.bd} p-3 active:scale-[0.98] transition-all cursor-pointer shadow-sm`}>
            {/* Background Illustration */}
            <div className="absolute bottom-0 right-0 left-0 h-12 opacity-[0.05] pointer-events-none">
               <svg viewBox="0 0 100 20" className="w-full h-full preserve-3d">
                  <path d="M0 10 Q 25 20 50 10 T 100 10 V 20 H 0 Z" fill="currentColor" className={s.c}/>
               </svg>
            </div>
            
            <div className="flex justify-between items-start relative z-10 mb-2">
               <div className={`p-2 ${s.iconBg} rounded-xl shadow-sm`}>
                  <s.icon className={`w-4 h-4 ${s.c}`}/>
               </div>
               <ChevronRight className="w-3 h-3 text-gray-300"/>
            </div>
            
            <div className="relative z-10">
               <p className="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1 truncate">{s.label}</p>
               <p className="text-lg font-black text-gray-900 tracking-tighter leading-none">{s.val}</p>
               <p className="text-[7px] font-bold text-gray-500 mt-1 uppercase tracking-tight truncate">{s.sub}</p>
            </div>
            
            {s.sub2 && (
               <div className="mt-2 pt-2 border-t border-black/5 relative z-10">
                  <p className="text-[9px] font-black text-gray-900 tracking-tight truncate">{s.sub2.split(' ')[0]}</p>
                  <p className="text-[7px] font-black text-gray-400 uppercase tracking-tighter truncate">{s.sub2.split(' ').slice(1).join(' ')}</p>
               </div>
            )}
          </div>
        ))}
      </div>

      <div className="grid grid-cols-3 md:grid-cols-3 gap-3 mb-4">
        {[
          { 
            id: 'drivers', 
            label: "Active Drivers", 
            val: stats?.active_drivers, 
            sub: "Registered", 
            icon: Users, 
            bg: "bg-indigo-50/50", 
            bd: "border-indigo-100", 
            c: "text-indigo-600",
            iconBg: "bg-indigo-100/50"
          },
          { 
            id: 'expenses', 
            label: "Total Expenses Today", 
            val: fmt(stats?.today_expenses), 
            sub: "Today", 
            icon: Activity, 
            bg: "bg-rose-50/50", 
            bd: "border-rose-100", 
            c: "text-rose-600",
            iconBg: "bg-rose-100/50"
          },
          { 
            id: 'coding', 
            label: "Coding Units Today", 
            val: stats?.coding_units, 
            sub: dayjs().format('dddd'), 
            icon: Calendar, 
            bg: "bg-purple-50/50", 
            bd: "border-purple-100", 
            c: "text-purple-600",
            iconBg: "bg-purple-100/50"
          },
        ].map((s) => (
          <div key={s.id} onClick={()=>setActiveModal(s.id)}
            className={`group relative overflow-hidden rounded-[1.25rem] ${s.bg} border ${s.bd} p-3 active:scale-[0.98] transition-all cursor-pointer shadow-sm`}>
            <div className="flex items-center gap-2 mb-2 relative z-10">
               <div className={`p-1.5 ${s.iconBg} rounded-lg shadow-sm`}>
                  <s.icon className={`w-3 h-3 ${s.c}`}/>
               </div>
               <p className="text-[7px] font-black text-gray-500 uppercase tracking-tighter truncate">{s.label.split(' ')[0]}</p>
            </div>
            <div className="relative z-10">
               <p className="text-xs font-black text-gray-900 tracking-tighter leading-none">{s.val}</p>
               <p className="text-[6px] font-bold text-gray-400 mt-1 uppercase tracking-tight truncate">{s.sub}</p>
            </div>
          </div>
        ))}
      </div>
      {/* 2. UNIT PERFORMANCE (Matching Web Sidebar Layout) */}
      <div className="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden mb-4">
        <div className="p-6 border-b border-gray-50 flex items-center justify-between">
          <div className="flex items-center gap-3">
             <div className="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                <BarChart3 className="w-6 h-6 text-blue-600"/>
             </div>
             <h3 className="font-black text-gray-900 uppercase tracking-tight">Unit Performance</h3>
          </div>
          <span className="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full uppercase tracking-widest border border-blue-100">Top 10 Performers</span>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4">
          {/* Main Chart Area */}
          <div className="md:col-span-3 p-4">
            <div className="h-[450px]">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={charts?.unitPerformance||[]} layout="vertical" margin={{ left: -10, right: 35, top: 10, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" horizontal={false} stroke="#f1f5f9" />
                  <XAxis type="number" hide />
                  <YAxis type="category" dataKey="plate" tick={{fontSize: 9, fontWeight: 900, fill: '#1e293b'}} axisLine={false} tickLine={false} width={80} />
                  <Tooltip 
                    cursor={{fill: '#f8fafc'}}
                    contentStyle={{borderRadius: 16, border: 'none', boxShadow: '0 12px 32px rgba(0,0,0,0.1)'}}
                    formatter={(v:any)=>fmt(v)}
                  />
                  {/* Target Bar (Hollow Amber) */}
                  <Bar dataKey="target" name="Monthly Target" fill="transparent" stroke="#fcd34d" strokeWidth={1.5} radius={[0, 4, 4, 0]} barSize={16}>
                    <LabelList dataKey="target" position="insideRight" style={{fontSize: 8, fontWeight: 900, fill: '#b45309'}} offset={8} formatter={(v:any)=>Math.round(v)} />
                  </Bar>
                  {/* Actual Bar (Solid Blue) */}
                  <Bar dataKey="actual" name="Actual Collection" fill="#3b82f6" radius={[0, 4, 4, 0]} barSize={8}>
                    <LabelList dataKey="actual" position="right" style={{fontSize: 8, fontWeight: 900, fill: '#3b82f6'}} offset={8} formatter={(v:any)=>v > 0 ? v.toFixed(2) : ''} />
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>

          {/* Sidebar Insights (Matching Web) */}
          <div className="bg-gray-50/50 p-6 border-l border-gray-100 flex flex-col gap-8">
            <div>
              <p className="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Fleet Health</p>
              <div className="flex items-end gap-2">
                <p className="text-4xl font-black text-gray-900 leading-none">{insights?.fleetHealth??0}%</p>
                <div className="flex items-center text-green-600 font-bold text-[10px] mb-1">
                   <TrendingUp className="w-3 h-3 mr-0.5"/> +2.4%
                </div>
              </div>
              <p className="text-[11px] text-gray-500 mt-2 font-medium leading-relaxed">
                Most units are meeting over 80% of their monthly boundary targets.
              </p>
            </div>

            <div className="pt-6 border-t border-gray-200">
              <p className="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Top Performer</p>
              <p className="text-xl font-black text-gray-900">{insights?.topPerformerUnit}</p>
              <p className="text-[11px] text-gray-500 mt-2 font-medium leading-relaxed">
                Consistency in daily collections makes this your most reliable asset.
              </p>
            </div>

            <div className="pt-6 border-t border-gray-200">
              <p className="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Legend</p>
              <div className="space-y-3">
                <div className="flex items-center gap-3">
                   <div className="w-4 h-4 rounded bg-[#3b82f6] shadow-sm"></div>
                   <span className="text-[10px] font-black text-gray-600 uppercase tracking-widest">Actual Collection</span>
                </div>
                <div className="flex items-center gap-3">
                   <div className="w-4 h-4 rounded border-2 border-[#fcd34d] bg-amber-50"></div>
                   <span className="text-[10px] font-black text-gray-600 uppercase tracking-widest">Monthly Target</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* 4. REVENUE TREND */}
      <div className="bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden mb-4">
        <div className="p-4 border-b border-gray-50 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center">
              <TrendingUp className="w-4 h-4 text-blue-600"/>
            </div>
            <h3 className="font-black text-gray-900 uppercase tracking-tight text-sm">Revenue Trend</h3>
          </div>
          <div className="flex gap-1">
            {[7, 30, 90, 365].map(d => (
              <button key={d} onClick={() => setDays(d)} className={`px-2 py-1 text-[8px] font-black rounded-lg transition-all ${days === d ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-50 text-gray-400'}`}>
                {d === 365 ? '1 YEAR' : d === 90 ? '3 MOS' : `${d} DAYS`}
              </button>
            ))}
          </div>
        </div>
        <div className="p-4 h-52">
          <ResponsiveContainer width="100%" height="100%">
            <AreaChart data={charts?.revenueTrend||[]} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
              <defs>
                <linearGradient id="colorRev" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.2}/>
                  <stop offset="95%" stopColor="#3b82f6" stopOpacity={0}/>
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9"/>
              <XAxis dataKey="date" tick={{fontSize:8, fontWeight:700, fill:'#94a3b8'}} axisLine={false} tickLine={false}/>
              <YAxis tick={{fontSize:8, fontWeight:700, fill:'#94a3b8'}} axisLine={false} tickLine={false} tickFormatter={(v)=>v >= 1000 ? `${v/1000}k` : v}/>
              <Tooltip 
                contentStyle={{borderRadius: 16, border: 'none', boxShadow: '0 12px 32px rgba(0,0,0,0.1)'}}
                formatter={(v:any)=>fmt(v)}
              />
              <Area type="monotone" dataKey="revenue" stroke="#3b82f6" strokeWidth={3} fillOpacity={1} fill="url(#colorRev)" />
            </AreaChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* 5. EXPENSE BREAKDOWN & WEEKLY OVERVIEW */}
      <div className="grid grid-cols-1 gap-4 mb-4">
          <div className="bg-white rounded-[2.5rem] border border-gray-100 shadow-lg p-6">
            <div className="flex items-center gap-3 mb-6">
               <div className="w-10 h-10 bg-rose-50 rounded-2xl flex items-center justify-center">
                  <PieChartIcon className="w-5 h-5 text-rose-500"/>
               </div>
               <h3 className="font-black text-gray-900 uppercase tracking-tight">Expense Distribution</h3>
            </div>
            <div className="h-48">
               <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie data={charts?.expenseBreakdown||[]} cx="35%" cy="50%" innerRadius={0} outerRadius={60} dataKey="value" stroke="#fff" strokeWidth={2}>
                      {(charts?.expenseBreakdown||[]).map((_:any,i:number)=><Cell key={i} fill={['#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899'][i % 5]}/>)}
                    </Pie>
                    <Tooltip contentStyle={{borderRadius: 12, border: 'none', boxShadow: '0 8px 24px rgba(0,0,0,0.1)'}}/>
                    <Legend layout="vertical" align="right" verticalAlign="middle" iconType="circle" wrapperStyle={{fontSize: 9, fontWeight: 700}} />
                  </PieChart>
               </ResponsiveContainer>
            </div>
          </div>

          <div className="bg-white rounded-[2rem] border border-gray-100 shadow-xl p-4">
            <div className="flex items-center justify-between mb-6">
              <div className="flex items-center gap-2">
                <div className="w-8 h-8 bg-indigo-50 rounded-xl flex items-center justify-center">
                  <LineChartIcon className="w-4 h-4 text-indigo-600"/>
                </div>
                <h3 className="font-black text-gray-900 uppercase tracking-tight text-sm">Weekly Overview</h3>
              </div>
            </div>
            <div className="p-4 h-64">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={charts?.weeklyData||[]} margin={{ top: 25, right: 30, left: -10, bottom: 0 }}>
                  <defs>
                    <linearGradient id="colorBoundary" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#eab308" stopOpacity={0.3}/>
                      <stop offset="95%" stopColor="#eab308" stopOpacity={0}/>
                    </linearGradient>
                    <linearGradient id="colorExpenses" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#ef4444" stopOpacity={0.3}/>
                      <stop offset="95%" stopColor="#ef4444" stopOpacity={0}/>
                    </linearGradient>
                    <linearGradient id="colorNet" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#22c55e" stopOpacity={0.3}/>
                      <stop offset="95%" stopColor="#22c55e" stopOpacity={0}/>
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9"/>
                  <XAxis dataKey="day" tick={{fontSize:9, fontWeight:700, fill:'#94a3b8'}} axisLine={false} tickLine={false}/>
                  <YAxis tick={{fontSize:9, fontWeight:700, fill:'#94a3b8'}} axisLine={false} tickLine={false} tickFormatter={(v)=>v !== 0 ? (Math.abs(v) >= 1000000 ? `${v/1000000}M` : `${v/1000}k`) : '0'}/>
                  <Tooltip 
                    contentStyle={{borderRadius: 16, border: 'none', boxShadow: '0 12px 32px rgba(0,0,0,0.1)', padding: '10px'}}
                  />
                  <Legend verticalAlign="top" align="center" iconType="circle" wrapperStyle={{fontSize: 10, fontWeight: 700, paddingBottom: 20}} />
                  
                  <Area type="monotone" dataKey="boundary" name="Boundary" stroke="#eab308" strokeWidth={3} fillOpacity={1} fill="url(#colorBoundary)" dot={{ r: 4, fill: '#eab308' }} activeDot={{ r: 6 }}>
                     <LabelList dataKey="boundary" position="top" offset={10} style={{fontSize: 7, fontWeight: 900, fill: '#854d0e'}} formatter={(v:any)=>v > 0 ? Math.round(v) : ''} />
                  </Area>
                  
                  <Area type="monotone" dataKey="expenses" name="Expenses" stroke="#ef4444" strokeWidth={3} fillOpacity={1} fill="url(#colorExpenses)" dot={{ r: 4, fill: '#ef4444' }} activeDot={{ r: 6 }}>
                     <LabelList dataKey="expenses" position="top" offset={10} style={{fontSize: 7, fontWeight: 900, fill: '#991b1b'}} formatter={(v:any)=>v > 0 ? (v >= 1000000 ? (v/1000000).toFixed(2)+'M' : Math.round(v)) : ''} />
                  </Area>
                  
                  <Area type="monotone" dataKey="net" name="Net Income" stroke="#22c55e" strokeWidth={3} fillOpacity={1} fill="url(#colorNet)" dot={{ r: 4, fill: '#22c55e' }} activeDot={{ r: 6 }}>
                     <LabelList dataKey="net" position="bottom" offset={10} style={{fontSize: 7, fontWeight: 900, fill: '#166534'}} formatter={(v:any)=>v !== 0 ? (Math.abs(v) >= 1000000 ? (v/1000000).toFixed(2)+'M' : Math.round(v)) : ''} />
                  </Area>
                  
                  <ReferenceLine y={0} stroke="#cbd5e1" strokeWidth={2} />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </div>
      </div>

      {/* 6. UNIT STATUS & TOP DRIVERS */}
      <div className="grid grid-cols-1 gap-4 mb-8">
          <div className="bg-white rounded-[2rem] border border-gray-100 shadow-lg p-6">
            <div className="flex items-center gap-3 mb-6">
               <div className="w-10 h-10 bg-emerald-50 rounded-2xl flex items-center justify-center">
                  <Activity className="w-5 h-5 text-emerald-500"/>
               </div>
               <h3 className="font-black text-gray-900 uppercase tracking-tight text-sm">Unit Status Distribution</h3>
            </div>
            <div className="h-56 relative">
               <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie 
                      data={charts?.unitStatusDist||[]} 
                      cx="50%" cy="50%" 
                      innerRadius={55} 
                      outerRadius={75} 
                      paddingAngle={4} 
                      dataKey="value"
                      stroke="none"
                    >
                      {(charts?.unitStatusDist||[]).map((_:any,i:number)=><Cell key={i} fill={['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#64748b'][i % 5]}/>)}
                    </Pie>
                    <Tooltip contentStyle={{borderRadius: 12, border: 'none', boxShadow: '0 8px 24px rgba(0,0,0,0.1)'}}/>
                    <Legend layout="vertical" align="right" verticalAlign="middle" iconType="circle" wrapperStyle={{fontSize: 9, fontWeight: 700}} />
                  </PieChart>
               </ResponsiveContainer>
                <div className="absolute top-1/2 left-[35%] -translate-y-1/2 -translate-x-1/2 text-center pointer-events-none">
                  <p className="text-xl font-black text-gray-900 leading-none">{stats?.active_units}</p>
                  <p className="text-[8px] font-black text-gray-400 uppercase tracking-widest">Total Units</p>
                </div>
            </div>
          </div>

          <div className="bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden">
             <div className="p-4 border-b border-gray-50 flex items-center justify-between">
                <div className="flex items-center gap-2">
                   <div className="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center">
                      <Users className="w-4 h-4 text-amber-600"/>
                   </div>
                   <h3 className="font-black text-gray-900 uppercase tracking-tight text-sm">Top Performing Drivers</h3>
                </div>
             </div>
              <div className="p-4 h-[350px]">
                <ResponsiveContainer width="100%" height="100%">
                  <BarChart data={charts?.topDrivers||[]} layout="vertical" margin={{ left: -10, right: 35, top: 0, bottom: 0 }}>
                    <CartesianGrid strokeDasharray="3 3" horizontal={false} stroke="#f1f5f9" />
                    <XAxis type="number" hide />
                    <YAxis type="category" dataKey="name" tick={{fontSize: 9, fontWeight: 700, fill: '#64748b'}} axisLine={false} tickLine={false} width={100} />
                    <Tooltip cursor={{fill: '#f8fafc'}} />
                    <Bar dataKey="score" radius={[0, 4, 4, 0]} barSize={16}>
                      {(charts?.topDrivers||[]).map((_: any, index: number) => (
                        <Cell key={`cell-${index}`} fill={['#3b82f6', '#8b5cf6', '#0d9488', '#64748b', '#ec4899'][index % 5]} />
                      ))}
                      <LabelList dataKey="score" position="right" style={{fontSize: 10, fontWeight: 900, fill: '#1e293b'}} offset={8} />
                    </Bar>
                  </BarChart>
                </ResponsiveContainer>
              </div>
          </div>

      </div>

      {/* MODALS - Minimal Update needed to match new theme */}
      {activeModal==="units" && <Modal title="Fleet Overview" color="bg-blue-600" onClose={()=>setActiveModal(null)}><FleetModal stats={stats} navigate={navigate} /></Modal>}
      {activeModal==="boundary" && <Modal title="Boundary Revenue" color="bg-emerald-600" onClose={()=>setActiveModal(null)}><BoundaryModal stats={stats} navigate={navigate} /></Modal>}
      {activeModal==="income" && <Modal title="Net Income" color="bg-green-600" onClose={()=>setActiveModal(null)}><IncomeModal stats={stats} /></Modal>}
      {activeModal==="maintenance" && <Modal title="Units Under Maintenance" color="bg-orange-500" onClose={()=>setActiveModal(null)}><MaintenanceModal modal={modal} /></Modal>}
      {activeModal==="drivers" && <Modal title="Active Drivers" color="bg-indigo-600" onClose={()=>setActiveModal(null)}><DriversModal modal={modal} /></Modal>}
      {activeModal==="expenses" && <Modal title="Total Expenses" color="bg-rose-500" onClose={()=>setActiveModal(null)}><ExpensesModal stats={stats} /></Modal>}
      {activeModal==="coding" && <Modal title="Coding Units Today" color="bg-violet-600" onClose={()=>setActiveModal(null)}><CodingModal stats={stats} modal={modal} /></Modal>}
    </div>
  );
}

// Sub-components for Modals
function FleetModal({stats, navigate}: any) {
  return (
    <div className="space-y-4">
      {[["Total Units","text-blue-600",stats?.active_units],["ROI Achieved","text-green-600",stats?.roi_achieved],["Under Maintenance","text-orange-600",stats?.maintenance_units],["Coding Today","text-violet-600",stats?.coding_units]].map(([l,c,v]:any)=>(
        <div key={l} className="flex justify-between items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
          <span className="text-sm font-bold text-gray-500 uppercase tracking-widest">{l}</span>
          <span className={`text-2xl font-black ${c}`}>{v??0}</span>
        </div>
      ))}
      <button onClick={()=>navigate("/units")} className="w-full bg-blue-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-100 active:scale-95 transition-all">View All Units</button>
    </div>
  );
}

function BoundaryModal({stats, navigate}: any) {
  return (
    <div className="space-y-4">
      <div className="p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100">
        <p className="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] mb-2">Today's Boundary</p>
        <p className="text-4xl font-black text-gray-900">{fmt(stats?.today_boundary)}</p>
      </div>
      <div className="p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100">
        <p className="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] mb-2">This Month's Boundary</p>
        <p className="text-4xl font-black text-gray-900">{fmt(stats?.month_boundary)}</p>
      </div>
      <button onClick={()=>navigate("/boundaries")} className="w-full bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-100 flex items-center justify-center gap-2 active:scale-95 transition-all">
        Full Boundary Logs <ChevronRight className="w-5 h-5"/>
      </button>
    </div>
  );
}

function IncomeModal({stats}: any) {
  return (
    <div className="space-y-3">
      {[["Today Boundary",stats?.today_boundary,"text-blue-600"],["Today Expenses",-(stats?.today_expenses||0),"text-red-600"],["Today Net Income",stats?.net_income,(stats?.net_income||0)>=0?"text-green-600":"text-red-600"],["Month Boundary",stats?.month_boundary,"text-blue-600"],["Month Net Income",stats?.net_income_month,(stats?.net_income_month||0)>=0?"text-green-600":"text-red-600"]].map(([l,v,c]:any)=>(
        <div key={l} className="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
          <span className="text-xs font-bold text-gray-500 uppercase tracking-widest">{l}</span>
          <span className={`font-black text-base ${c}`}>{fmt(v)}</span>
        </div>
      ))}
    </div>
  );
}

function MaintenanceModal({modal}: any) {
  return (
    <div className="space-y-4">
      <div className="grid grid-cols-3 gap-3">
        {[["Total",(modal?.maintenanceList||[]).length,"text-orange-600"],["Preventive",(modal?.maintenanceList||[]).filter((m:any)=>m.type?.toLowerCase()==="preventive").length,"text-blue-600"],["Emergency",(modal?.maintenanceList||[]).filter((m:any)=>m.type?.toLowerCase()==="emergency").length,"text-red-600"]].map(([l,v,c]:any)=>(
          <div key={l} className="bg-gray-50 rounded-2xl p-4 text-center border border-gray-100">
            <p className={`text-2xl font-black ${c}`}>{v}</p>
            <p className="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{l}</p>
          </div>
        ))}
      </div>
      <div className="space-y-3">
        {(modal?.maintenanceList||[]).map((m:any)=>(
          <div key={m.id} className="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div className="flex justify-between items-start mb-2">
              <div>
                <p className="font-black text-gray-900">{m.plate_number}</p>
                <p className="text-xs text-gray-400 font-medium">{m.driver_name?.trim()||"No driver assigned"}</p>
              </div>
              <span className={`text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest ${m.type?.toLowerCase()==="emergency"?"bg-red-50 text-red-600 border border-red-100":"bg-orange-50 text-orange-600 border border-orange-100"}`}>{m.type||"N/A"}</span>
            </div>
            <p className="text-xs text-gray-500 leading-relaxed mb-3">{m.description||"System generated maintenance ticket."}</p>
            <div className="flex items-center justify-between pt-3 border-t border-gray-50">
               <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Entry: {m.date_started}</span>
               <span className="text-sm font-black text-gray-900">{fmt(m.cost)}</span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function DriversModal({modal}: any) {
  return (
    <div className="space-y-4">
      <div className="grid grid-cols-3 gap-3">
        {[["Total",(modal?.driversList||[]).length,"text-blue-600"],["With Unit",(modal?.driversList||[]).filter((d:any)=>d.plate_number).length,"text-green-600"],["Vacant",(modal?.driversList||[]).filter((d:any)=>!d.plate_number).length,"text-orange-600"]].map(([l,v,c]:any)=>(
          <div key={l} className="bg-gray-50 rounded-2xl p-4 text-center border border-gray-100">
            <p className={`text-2xl font-black ${c}`}>{v}</p>
            <p className="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{l}</p>
          </div>
        ))}
      </div>
      <div className="space-y-3">
        {(modal?.driversList||[]).map((d:any)=>(
          <div key={d.id} className="bg-white rounded-[1.5rem] p-4 border border-gray-100 flex items-center gap-4">
            <div className="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center font-black text-indigo-600 text-lg">{d.first_name?.charAt(0)}</div>
            <div className="flex-1 min-w-0">
              <p className="font-black text-gray-900 text-sm leading-none mb-1">{d.first_name} {d.last_name}</p>
              <p className="text-[10px] text-gray-400 font-medium">{d.contact_number||"NO CONTACT RECORDED"}</p>
              {d.plate_number && <div className="mt-2 flex items-center gap-1.5"><Car className="w-3 h-3 text-blue-500"/><span className="text-[10px] font-black text-blue-600 uppercase tracking-widest">UNIT: {d.plate_number}</span></div>}
            </div>
            <span className={`text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest ${d.plate_number?"bg-green-50 text-green-600 border border-green-100":"bg-gray-50 text-gray-400 border border-gray-100"}`}>{d.plate_number?"Deployed":"Available"}</span>
          </div>
        ))}
      </div>
    </div>
  );
}

function ExpensesModal({stats}: any) {
  return (
    <div className="space-y-4">
      {[["General Expenses",stats?.expense_general,"#ef4444"],["Salary",stats?.expense_salary,"#f59e0b"],["Maintenance",stats?.expense_maintenance,"#8b5cf6"]].map(([l,v,c]:any)=>(
        <div key={l} className="flex justify-between items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
          <div className="flex items-center gap-3"><div className="w-4 h-4 rounded-lg shadow-sm" style={{background:c}}/><span className="text-sm font-bold text-gray-600 uppercase tracking-widest">{l}</span></div>
          <span className="font-black text-gray-900">{fmt(v)}</span>
        </div>
      ))}
      <div className="flex justify-between items-center p-6 bg-rose-50 rounded-[2rem] border border-rose-100 mt-4">
        <span className="font-black text-rose-600 uppercase tracking-[0.2em]">Total Daily</span>
        <span className="font-black text-rose-600 text-3xl tracking-tighter">{fmt(stats?.today_expenses)}</span>
      </div>
    </div>
  );
}

function CodingModal({stats, modal}: any) {
  return (
    <div className="space-y-4">
      <div className="bg-violet-50 rounded-[2.5rem] p-8 border border-violet-100 text-center">
        <p className="text-5xl font-black text-violet-700 tracking-tighter mb-1">{stats?.coding_units??0}</p>
        <p className="text-xs text-violet-500 font-black uppercase tracking-[0.2em] mb-2">Fleet Under Coding</p>
        <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{new Date().toLocaleDateString("en-PH",{weekday:"long", month:"long", day:"numeric"})}</p>
      </div>
      <div className="grid grid-cols-2 gap-3">
        {(modal?.codingList||[]).map((u:any,i:number)=>(
          <div key={i} className="bg-white rounded-2xl p-4 border border-gray-100 flex items-center gap-3 shadow-sm active:bg-gray-50 transition-colors">
            <div className="p-2 bg-violet-50 rounded-xl"><Calendar className="w-4 h-4 text-violet-500"/></div>
            <span className="font-black text-gray-900 tracking-tight">{u.plate_number}</span>
          </div>
        ))}
        {!(modal?.codingList||[]).length&&<p className="col-span-2 text-center text-gray-400 py-10 text-sm font-medium italic">No units restricted today.</p>}
      </div>
    </div>
  );
}

