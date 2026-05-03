import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { Car, Users, TrendingUp, Wrench, DollarSign, Calendar, Activity, BarChart3, X, ChevronRight, Loader2, RefreshCw } from "lucide-react";
import { LineChart, Line, BarChart, Bar, PieChart, Pie, Cell, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from "recharts";
import api from "../services/api";
import { toast } from "sonner";

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
  const [stats, setStats] = useState<any>(null);
  const [charts, setCharts] = useState<any>(null);
  const [modal, setModal] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [activeModal, setActiveModal] = useState<string|null>(null);
  const [days, setDays] = useState(7);
  const [error, setError] = useState<string|null>(null);

  useEffect(()=>{ load(); },[]);
  useEffect(()=>{ if(stats) loadTrend(); },[days]);

  const load = async () => {
    setLoading(true); setError(null);
    try {
      const r = await api.get("/dashboard?days="+days);
      if(r.data.success){
        setStats(r.data.stats);
        setCharts(r.data.chartData);
        setModal(r.data.modalData);
      } else { setError("Server returned error"); }
    } catch(e:any) {
      const msg = e?.response?.data?.message || e?.message || "Network error";
      setError(msg);
      toast.error("Dashboard error: "+msg);
    } finally { setLoading(false); }
  };

  const loadTrend = async () => {
    try {
      const r = await api.get("/dashboard?days="+days);
      if(r.data.success) setCharts((p:any)=>({...p, revenueTrend: r.data.chartData?.revenueTrend||[]}));
    } catch{}
  };

  if(loading) return (
    <div className="flex flex-col items-center justify-center min-h-[60vh] gap-3">
      <Loader2 className="w-8 h-8 animate-spin text-blue-600"/>
      <p className="text-gray-500 text-sm">Loading dashboard...</p>
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

  const cards = [
    {id:"units",    label:"Total Units",       val:stats?.active_units??0,             sub:`${stats?.roi_achieved??0} ROI Achieved`, icon:Car,      c:"bg-blue-500",   bg:"from-blue-50 to-indigo-50",   bd:"border-blue-100"},
    {id:"boundary", label:"Boundary Revenue",  val:fmt(stats?.today_boundary),          sub:`${fmt(stats?.month_boundary)} this month`, icon:DollarSign, c:"bg-emerald-500",bg:"from-emerald-50 to-teal-50",  bd:"border-emerald-100"},
    {id:"income",   label:"Net Income (Kita)",  val:fmt(stats?.net_income),              sub:`${fmt(stats?.net_income_month)} this month`, icon:TrendingUp, c:"bg-green-500",  bg:"from-green-50 to-lime-50",    bd:"border-green-100"},
    {id:"maintenance",label:"Under Maintenance",val:stats?.maintenance_units??0,         sub:"Ongoing maintenance",   icon:Wrench,    c:"bg-orange-500", bg:"from-orange-50 to-amber-50",  bd:"border-orange-100"},
    {id:"drivers",  label:"Active Drivers",    val:stats?.active_drivers??0,            sub:"Registered drivers",    icon:Users,     c:"bg-indigo-500", bg:"from-indigo-50 to-violet-50", bd:"border-indigo-100"},
    {id:"expenses", label:"Total Expenses",    val:fmt(stats?.today_expenses),          sub:"Today total",           icon:Activity,  c:"bg-rose-500",   bg:"from-rose-50 to-red-50",      bd:"border-rose-100"},
    {id:"coding",   label:"Coding Units Today", val:stats?.coding_units??0,             sub:new Date().toLocaleDateString("en-PH",{weekday:"long"}), icon:Calendar, c:"bg-violet-500", bg:"from-violet-50 to-purple-50", bd:"border-violet-100"},
  ];

  return (
    <div className="space-y-5 pb-10">
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-2xl font-black text-gray-900">Dashboard</h2>
          <p className="text-xs text-gray-400">{new Date().toLocaleDateString("en-PH",{weekday:"long",year:"numeric",month:"long",day:"numeric"})}</p>
        </div>
        <button onClick={load} className="p-2 bg-gray-100 rounded-xl"><RefreshCw className="w-4 h-4 text-gray-500"/></button>
      </div>

      <div className="grid grid-cols-2 gap-3">
        {cards.map(s=>(
          <div key={s.id} onClick={()=>setActiveModal(s.id)}
            className={`cursor-pointer relative overflow-hidden rounded-2xl bg-gradient-to-br ${s.bg} border ${s.bd} p-4 active:scale-95 transition-all`}>
            <div className="flex items-start justify-between mb-2">
              <div className={`${s.c} p-2 rounded-xl`}><s.icon className="w-4 h-4 text-white"/></div>
              <ChevronRight className="w-4 h-4 text-gray-300"/>
            </div>
            <p className="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">{s.label}</p>
            <p className="text-xl font-black text-gray-900 leading-none">{s.val}</p>
            <p className="text-[10px] text-gray-400 mt-1">{s.sub}</p>
          </div>
        ))}
      </div>

      {charts ? (
        <>
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div className="p-4 border-b flex items-center justify-between">
              <div className="flex items-center gap-2"><BarChart3 className="w-4 h-4 text-blue-600"/><span className="font-bold text-sm">Revenue Trend</span></div>
              <div className="flex gap-1">
                {[{d:7,l:"7D"},{d:30,l:"30D"},{d:90,l:"3M"},{d:365,l:"1Y"}].map(b=>(
                  <button key={b.d} onClick={()=>setDays(b.d)} className={`px-2 py-1 text-[10px] font-bold rounded-lg ${days===b.d?"bg-blue-600 text-white":"bg-gray-100 text-gray-600"}`}>{b.l}</button>
                ))}
              </div>
            </div>
            <div className="p-2 h-52">
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={charts.revenueTrend||[]}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" vertical={false}/>
                  <XAxis dataKey="date" tick={{fontSize:8}} axisLine={false} tickLine={false}/>
                  <YAxis tick={{fontSize:8}} axisLine={false} tickLine={false} width={40}/>
                  <Tooltip formatter={(v:any)=>fmt(v)} contentStyle={{borderRadius:8,border:"none",boxShadow:"0 4px 15px rgba(0,0,0,0.1)"}}/>
                  <Legend iconType="circle" wrapperStyle={{fontSize:9}}/>
                  <Line type="monotone" dataKey="revenue" name="Revenue" stroke="#3b82f6" strokeWidth={2} dot={false}/>
                  <Line type="monotone" dataKey="expenses" name="Expenses" stroke="#ef4444" strokeWidth={2} dot={false}/>
                  <Line type="monotone" dataKey="netIncome" name="Net Income" stroke="#10b981" strokeWidth={2} dot={false}/>
                </LineChart>
              </ResponsiveContainer>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div className="p-4 border-b flex items-center justify-between">
              <div className="flex items-center gap-2"><BarChart3 className="w-4 h-4 text-blue-600"/><span className="font-bold text-sm">Unit Performance</span></div>
              <span className="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Top 10 This Month</span>
            </div>
            <div className="p-2 h-60">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={charts.unitPerformance||[]} layout="vertical">
                  <CartesianGrid strokeDasharray="3 3" horizontal={false} stroke="#f0f0f0"/>
                  <XAxis type="number" tick={{fontSize:8}} axisLine={false} tickLine={false}/>
                  <YAxis type="category" dataKey="plate" tick={{fontSize:8}} axisLine={false} tickLine={false} width={55}/>
                  <Tooltip formatter={(v:any)=>fmt(v)} contentStyle={{borderRadius:8,border:"none"}}/>
                  <Legend iconType="circle" wrapperStyle={{fontSize:9}}/>
                  <Bar dataKey="actual" name="Collected" fill="#3b82f6" radius={[0,4,4,0]}/>
                  <Bar dataKey="target" name="Target" fill="#fcd34d" radius={[0,4,4,0]}/>
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
              <p className="p-3 border-b text-sm font-bold">Expenses</p>
              <div className="h-44">
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie data={charts.expenseBreakdown||[]} cx="50%" cy="50%" innerRadius={35} outerRadius={55} paddingAngle={3} dataKey="value">
                      {(charts.expenseBreakdown||[]).map((_:any,i:number)=><Cell key={i} fill={COLORS[i%COLORS.length]}/>)}
                    </Pie>
                    <Tooltip formatter={(v:any)=>fmt(v)} contentStyle={{borderRadius:8,border:"none"}}/>
                    <Legend iconType="circle" wrapperStyle={{fontSize:8}}/>
                  </PieChart>
                </ResponsiveContainer>
              </div>
            </div>
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
              <p className="p-3 border-b text-sm font-bold">Unit Status</p>
              <div className="h-44">
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie data={charts.unitStatusDist||[]} cx="50%" cy="50%" innerRadius={35} outerRadius={55} paddingAngle={3} dataKey="value">
                      {(charts.unitStatusDist||[]).map((_:any,i:number)=><Cell key={i} fill={COLORS[i%COLORS.length]}/>)}
                    </Pie>
                    <Tooltip contentStyle={{borderRadius:8,border:"none"}}/>
                    <Legend iconType="circle" wrapperStyle={{fontSize:8}}/>
                  </PieChart>
                </ResponsiveContainer>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
            <p className="p-3 border-b text-sm font-bold">Weekly Overview</p>
            <div className="p-2 h-48">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={charts.weeklyData||[]}>
                  <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f0f0f0"/>
                  <XAxis dataKey="day" tick={{fontSize:8}} axisLine={false} tickLine={false}/>
                  <YAxis tick={{fontSize:8}} axisLine={false} tickLine={false} width={40}/>
                  <Tooltip formatter={(v:any)=>fmt(v)} contentStyle={{borderRadius:8,border:"none"}}/>
                  <Legend iconType="circle" wrapperStyle={{fontSize:9}}/>
                  <Bar dataKey="boundary" name="Boundary" fill="#3b82f6" radius={[4,4,0,0]}/>
                  <Bar dataKey="expenses" name="Expenses" fill="#ef4444" radius={[4,4,0,0]}/>
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
            <p className="p-3 border-b text-sm font-bold">Top Drivers This Month</p>
            <div className="p-2 h-52">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={charts.topDrivers||[]} layout="vertical">
                  <CartesianGrid strokeDasharray="3 3" horizontal={false} stroke="#f0f0f0"/>
                  <XAxis type="number" tick={{fontSize:8}} axisLine={false} tickLine={false}/>
                  <YAxis type="category" dataKey="name" tick={{fontSize:8}} axisLine={false} tickLine={false} width={70}/>
                  <Tooltip formatter={(v:any)=>fmt(v)} contentStyle={{borderRadius:8,border:"none"}}/>
                  <Bar dataKey="total" name="Collected" fill="#10b981" radius={[0,4,4,0]}/>
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>
        </>
      ) : (
        <div className="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-center">
          <p className="text-amber-700 font-bold text-sm">Charts loading...</p>
          <p className="text-amber-600 text-xs mt-1">No chart data returned from server yet.</p>
        </div>
      )}

      {activeModal==="maintenance" && (
        <Modal title="Units Under Maintenance" color="bg-gradient-to-r from-orange-500 to-amber-500" onClose={()=>setActiveModal(null)}>
          <div className="grid grid-cols-3 gap-2 mb-4">
            {[["Total","text-orange-600",(modal?.maintenanceList||[]).length],["Preventive","text-blue-600",(modal?.maintenanceList||[]).filter((m:any)=>m.type?.toLowerCase()==="preventive").length],["Emergency","text-red-600",(modal?.maintenanceList||[]).filter((m:any)=>m.type?.toLowerCase()==="emergency").length]].map(([l,c,v]:any)=>(
              <div key={l} className="bg-gray-50 rounded-xl p-3 text-center border"><p className={`text-xl font-black ${c}`}>{v}</p><p className="text-[10px] text-gray-500 font-bold uppercase">{l}</p></div>
            ))}
          </div>
          <div className="space-y-3">
            {(modal?.maintenanceList||[]).map((m:any)=>(
              <div key={m.id} className="bg-gray-50 rounded-xl p-3 border">
                <div className="flex justify-between items-start">
                  <div><p className="font-bold text-sm">{m.plate_number}</p><p className="text-xs text-gray-500">{m.driver_name?.trim()||"No driver"}</p></div>
                  <span className={`text-[10px] font-bold px-2 py-1 rounded-full ${m.type?.toLowerCase()==="emergency"?"bg-red-100 text-red-700":"bg-orange-100 text-orange-700"}`}>{m.type||"N/A"}</span>
                </div>
                <p className="text-xs text-gray-500 mt-1">{m.description||"No description"}</p>
                <p className="text-[10px] text-gray-400 mt-1">Cost: {fmt(m.cost)} · {m.date_started}</p>
              </div>
            ))}
            {!(modal?.maintenanceList||[]).length&&<p className="text-center text-gray-400 py-8 text-sm">No maintenance records.</p>}
          </div>
        </Modal>
      )}

      {activeModal==="drivers" && (
        <Modal title="Active Drivers" color="bg-gradient-to-r from-indigo-500 to-purple-500" onClose={()=>setActiveModal(null)}>
          <div className="grid grid-cols-3 gap-2 mb-4">
            {[["Total","text-blue-600",(modal?.driversList||[]).length],["With Unit","text-green-600",(modal?.driversList||[]).filter((d:any)=>d.plate_number).length],["Vacant","text-orange-600",(modal?.driversList||[]).filter((d:any)=>!d.plate_number).length]].map(([l,c,v]:any)=>(
              <div key={l} className="bg-gray-50 rounded-xl p-3 text-center border"><p className={`text-xl font-black ${c}`}>{v}</p><p className="text-[10px] text-gray-500 font-bold uppercase">{l}</p></div>
            ))}
          </div>
          <div className="space-y-2">
            {(modal?.driversList||[]).map((d:any)=>(
              <div key={d.id} className="bg-gray-50 rounded-xl p-3 border flex items-center gap-3">
                <div className="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center font-black text-indigo-600 flex-shrink-0 text-sm">{d.first_name?.charAt(0)||"?"}</div>
                <div className="flex-1 min-w-0">
                  <p className="font-bold text-sm">{d.first_name} {d.last_name}</p>
                  <p className="text-xs text-gray-400">{d.contact_number||"No contact"}</p>
                  {d.plate_number&&<p className="text-[10px] text-blue-600 font-bold">Unit: {d.plate_number}</p>}
                </div>
                <span className={`text-[10px] font-bold px-2 py-1 rounded-full ${d.plate_number?"bg-green-100 text-green-700":"bg-gray-100 text-gray-500"}`}>{d.plate_number?"Active":"Vacant"}</span>
              </div>
            ))}
          </div>
        </Modal>
      )}

      {activeModal==="income" && (
        <Modal title="Net Income Details" color="bg-gradient-to-r from-green-500 to-emerald-600" onClose={()=>setActiveModal(null)}>
          <div className="space-y-3">
            {[["Today's Boundary",stats?.today_boundary,"text-blue-600"],["Today's Expenses",-(stats?.today_expenses||0),"text-red-600"],["Today's Net Income",stats?.net_income,(stats?.net_income||0)>=0?"text-green-600":"text-red-600"],["Month Boundary",stats?.month_boundary,"text-blue-600"],["Month Net Income",stats?.net_income_month,(stats?.net_income_month||0)>=0?"text-green-600":"text-red-600"]].map(([l,v,c]:any)=>(
              <div key={l} className="flex justify-between items-center p-3 bg-gray-50 rounded-xl border">
                <span className="text-sm text-gray-600">{l}</span>
                <span className={`font-black text-sm ${c}`}>{fmt(v)}</span>
              </div>
            ))}
            <div className="p-3 bg-gray-50 rounded-xl border space-y-2 mt-2">
              <p className="text-xs font-bold text-gray-500 uppercase">Today Expense Breakdown</p>
              {[["General",stats?.expense_general],["Salary",stats?.expense_salary],["Maintenance",stats?.expense_maintenance]].map(([l,v]:any)=>(
                <div key={l} className="flex justify-between text-sm"><span className="text-gray-500">{l}</span><span className="font-bold text-red-500">-{fmt(v)}</span></div>
              ))}
            </div>
          </div>
        </Modal>
      )}

      {activeModal==="expenses" && (
        <Modal title="Today's Expenses" color="bg-gradient-to-r from-rose-500 to-red-500" onClose={()=>setActiveModal(null)}>
          <div className="space-y-3">
            {[["General Expenses",stats?.expense_general,"#ef4444"],["Salary",stats?.expense_salary,"#f59e0b"],["Maintenance",stats?.expense_maintenance,"#8b5cf6"]].map(([l,v,c]:any)=>(
              <div key={l} className="flex justify-between items-center p-4 bg-gray-50 rounded-xl border">
                <div className="flex items-center gap-3"><div className="w-3 h-3 rounded-full" style={{background:c}}/><span className="text-sm">{l}</span></div>
                <span className="font-black">{fmt(v)}</span>
              </div>
            ))}
            <div className="flex justify-between items-center p-4 bg-rose-50 rounded-xl border border-rose-200">
              <span className="font-bold">Total</span><span className="font-black text-rose-600 text-lg">{fmt(stats?.today_expenses)}</span>
            </div>
          </div>
        </Modal>
      )}

      {activeModal==="boundary" && (
        <Modal title="Boundary Revenue" color="bg-gradient-to-r from-emerald-500 to-teal-500" onClose={()=>setActiveModal(null)}>
          <div className="space-y-3">
            <div className="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
              <p className="text-xs font-bold text-emerald-600 uppercase mb-1">Today's Boundary</p>
              <p className="text-3xl font-black">{fmt(stats?.today_boundary)}</p>
            </div>
            <div className="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
              <p className="text-xs font-bold text-emerald-600 uppercase mb-1">This Month's Boundary</p>
              <p className="text-3xl font-black">{fmt(stats?.month_boundary)}</p>
            </div>
            <button onClick={()=>{setActiveModal(null);navigate("/boundaries");}} className="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2">
              View All Boundaries <ChevronRight className="w-4 h-4"/>
            </button>
          </div>
        </Modal>
      )}

      {activeModal==="coding" && (
        <Modal title="Coding Units Today" color="bg-gradient-to-r from-violet-500 to-purple-600" onClose={()=>setActiveModal(null)}>
          <div className="bg-violet-50 rounded-2xl p-4 border border-violet-100 mb-4 text-center">
            <p className="text-4xl font-black text-violet-700">{stats?.coding_units??0}</p>
            <p className="text-xs text-violet-600 font-bold uppercase">Units on Coding Today</p>
            <p className="text-xs text-gray-400 mt-1">{new Date().toLocaleDateString("en-PH",{weekday:"long"})}</p>
          </div>
          <div className="space-y-2">
            {(modal?.codingList||[]).map((u:any,i:number)=>(
              <div key={i} className="bg-gray-50 rounded-xl p-3 border flex items-center gap-3">
                <Calendar className="w-4 h-4 text-violet-500"/>
                <span className="font-bold text-sm">{u.plate_number}</span>
              </div>
            ))}
            {!(modal?.codingList||[]).length&&<p className="text-center text-gray-400 py-6 text-sm">No coding units today.</p>}
          </div>
        </Modal>
      )}

      {activeModal==="units" && (
        <Modal title="Fleet Overview" color="bg-gradient-to-r from-blue-500 to-indigo-600" onClose={()=>setActiveModal(null)}>
          <div className="space-y-3">
            {[["Total Units","text-blue-600",stats?.active_units],["ROI Achieved","text-green-600",stats?.roi_achieved],["Under Maintenance","text-orange-600",stats?.maintenance_units],["Coding Today","text-violet-600",stats?.coding_units]].map(([l,c,v]:any)=>(
              <div key={l} className="flex justify-between items-center p-4 bg-gray-50 rounded-xl border">
                <span className="text-sm text-gray-600">{l}</span>
                <span className={`text-2xl font-black ${c}`}>{v??0}</span>
              </div>
            ))}
            <button onClick={()=>{setActiveModal(null);navigate("/units");}} className="w-full bg-blue-600 text-white font-bold py-3 rounded-xl">View All Units</button>
          </div>
        </Modal>
      )}
    </div>
  );
}
