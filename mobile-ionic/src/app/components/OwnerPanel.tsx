import { useState, useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";
import { toast } from "sonner";
import {
  Crown, Shield, Users, UserPlus, Activity, Lock, ShieldAlert,
  CheckCircle, XCircle, RefreshCw, Loader2, X, ChevronRight,
  LayoutDashboard, Eye, EyeOff, Search, Key
} from "lucide-react";

export function OwnerPanel() {
  const { user } = useAuth();
  const [tab, setTab] = useState("page_access");
  const [data, setData] = useState<any>(null);
  const [auditLogs, setAuditLogs] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string|null>(null);
  const [search, setSearch] = useState("");

  // Staff form
  const [sf, setSf] = useState({first_name:"",last_name:"",email:"",phone_number:"",address:"",role:""});
  const [submitting, setSubmitting] = useState(false);
  const [tempPass, setTempPass] = useState("");

  // Security form
  const [archForm, setArchForm] = useState({archive_password:"",archive_password_confirmation:""});
  const [showPass, setShowPass] = useState(false);

  // Page access logic
  const [accessUser, setAccessUser] = useState<any>(null);
  const [selectedPages, setSelectedPages] = useState<string[]>([]);
  const [savingAccess, setSavingAccess] = useState(false);

  useEffect(() => { loadData(); }, []);
  useEffect(() => { if (tab === "login_history") loadAudit(); }, [tab]);

  const loadData = async () => {
    setLoading(true); setError(null);
    try {
      const r = await api.get("/super-admin/overview");
      if (r.data.success) {
        setData(r.data);
        if (accessUser) {
          // Keep access user updated if it was selected
          const updatedUser = r.data.allUsers.find((u:any) => u.id === accessUser.id);
          if (updatedUser) {
            setAccessUser(updatedUser);
            setSelectedPages(updatedUser.allowed_pages || []);
          }
        }
      }
      else setError("Server returned error.");
    } catch(e:any) {
      const msg = e?.response?.data?.message || e?.message || "Connection failed";
      setError(msg);
      toast.error("Owner Panel: " + msg);
    } finally { setLoading(false); }
  };

  const loadAudit = async () => {
    try {
      const r = await api.get("/super-admin/audit?per_page=50");
      setAuditLogs(r.data.data || []);
    } catch(e:any) { toast.error("Audit: " + (e?.response?.data?.message || "Failed")); }
  };

  const createStaff = async (e: React.FormEvent) => {
    e.preventDefault(); setSubmitting(true);
    try {
      const r = await api.post("/super-admin/staff", sf);
      if (r.data.success) { toast.success(r.data.message); setTempPass(r.data.temp_password); loadData(); }
      else toast.error(r.data.message);
    } catch(e:any) { toast.error(e?.response?.data?.message || "Failed to create staff."); }
    finally { setSubmitting(false); }
  };

  const savePageAccess = async () => {
    if (!accessUser) return;
    setSavingAccess(true);
    try {
      const r = await api.post(`/super-admin/users/${accessUser.id}/page-access`, { pages: selectedPages });
      toast.success(r.data.message);
      loadData();
    } catch(e:any) {
      toast.error(e?.response?.data?.message || "Failed to update page access.");
    } finally {
      setSavingAccess(false);
    }
  };

  if (user?.role !== "super_admin") return (
    <div className="flex flex-col items-center justify-center min-h-[400px] gap-4 p-6">
      <Shield className="w-16 h-16 text-red-400"/>
      <h2 className="text-xl font-black text-gray-900">Access Denied</h2>
      <p className="text-gray-500 text-sm text-center">You need Owner (Super Admin) privileges to access this panel.</p>
    </div>
  );

  const tabs = [
    {id:"overview", label:"OVERVIEW", icon:LayoutDashboard},
    {id:"create_staff", label:"CREATE STAFF", icon:UserPlus},
    {id:"all_users", label:"ALL USERS", icon:Users},
    {id:"page_access", label:"PAGE ACCESS", icon:Shield},
    {id:"login_history", label:"LOGIN HISTORY", icon:Activity},
    {id:"system_security", label:"SYSTEM SECURITY", icon:Lock},
  ];

  const filteredUsers = (data?.allUsers||[]).filter((u:any) =>
    u.full_name?.toLowerCase().includes(search.toLowerCase()) ||
    u.email?.toLowerCase().includes(search.toLowerCase()) ||
    u.role?.toLowerCase().includes(search.toLowerCase())
  );

  const statusColor = (s:string) => s==="approved"?"bg-green-100 text-green-700":s==="pending"?"bg-amber-100 text-amber-700":"bg-red-100 text-red-700";

  // Page definitions matching Laravel backend
  const pageGroups = [
    {
      title: "1. CORE MANAGEMENT",
      pages: [
        { id: "dashboard", label: "DASHBOARD" },
        { id: "units.*", label: "UNIT MANAGEMENT" },
        { id: "driver-management.*", label: "DRIVER MANAGEMENT" },
        { id: "activity-logs.*", label: "HISTORY LOGS" }
      ]
    },
    {
      title: "2. OPERATIONS",
      pages: [
        { id: "live-tracking.*", label: "LIVE TRACKING" },
        { id: "maintenance.*", label: "MAINTENANCE" },
        { id: "coding.*", label: "CODING MANAGEMENT" },
        { id: "driver-behavior.*", label: "DRIVER BEHAVIOR" },
        { id: "spare-parts.*", label: "SPARE PARTS INVENTORY" },
        { id: "suppliers.*", label: "SUPPLIERS" }
      ]
    },
    {
      title: "3. FINANCIAL",
      pages: [
        { id: "boundaries.*", label: "BOUNDARIES" },
        { id: "office-expenses.*", label: "OFFICE EXPENSES" },
        { id: "salary.*", label: "SALARY MANAGEMENT" },
        { id: "boundary-rules.*", label: "BOUNDARY RULES" }
      ]
    },
    {
      title: "4. LEGAL & ADMIN",
      pages: [
        { id: "decision-management.*", label: "FRANCHISE" },
        { id: "staff.*", label: "STAFF RECORDS" },
        { id: "archive.*", label: "ARCHIVE ACCESS" }
      ]
    },
    {
      title: "5. REPORTS",
      pages: [
        { id: "analytics.*", label: "ANALYTICS" },
        { id: "profitability.*", label: "UNIT PROFITABILITY" }
      ]
    }
  ];

  const togglePage = (pageId: string) => {
    if (selectedPages.includes(pageId)) {
      setSelectedPages(selectedPages.filter(id => id !== pageId));
    } else {
      setSelectedPages([...selectedPages, pageId]);
    }
  };

  const selectAllPages = () => {
    setSelectedPages(pageGroups.flatMap(g => g.pages.map(p => p.id)));
  };

  return (
    <div className="space-y-6 pb-10">
      {/* Header matching web app */}
      <div className="bg-[#fffdf0] border border-[#fef0c7] border-l-[6px] border-l-amber-500 rounded-2xl p-6 shadow-sm">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex items-center gap-4">
            <div className="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center shadow-sm">
              <Crown className="w-8 h-8 text-white"/>
            </div>
            <div>
              <div className="flex items-center gap-2 mb-1">
                <h1 className="text-2xl font-black text-gray-900 tracking-tight">Owner Control Center</h1>
                <span className="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded uppercase">Owner</span>
              </div>
              <p className="text-gray-500 text-sm">Welcome back, <span className="font-bold text-gray-900">{user?.full_name}</span> Full system access</p>
            </div>
          </div>

          {/* Quick stats */}
          {data && (
            <div className="flex items-center gap-6">
              <div className="text-center">
                <p className="text-2xl font-black text-green-600">{data.stats?.active_users??0}</p>
                <p className="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Active</p>
              </div>
              <div className="text-center">
                <p className="text-2xl font-black text-gray-900">{data.stats?.total_users??0}</p>
                <p className="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Total Users</p>
              </div>
            </div>
          )}
        </div>
        
        {/* Tabs */}
        <div className="flex overflow-x-auto gap-6 mt-8 border-b border-gray-200 scrollbar-hide">
          {tabs.map(t=>(
            <button key={t.id} onClick={()=>setTab(t.id)}
              className={`flex items-center gap-2 pb-3 text-sm font-bold whitespace-nowrap transition-all border-b-2
                ${tab===t.id?"border-amber-500 text-amber-700":"border-transparent text-gray-500 hover:text-gray-900"}`}>
              <t.icon className="w-4 h-4"/>{t.label}
            </button>
          ))}
        </div>
      </div>

      {loading ? (
        <div className="flex justify-center p-10"><Loader2 className="w-8 h-8 animate-spin text-amber-500"/></div>
      ) : error ? (
        <div className="bg-red-50 border border-red-200 rounded-2xl p-5 text-center">
          <XCircle className="w-10 h-10 text-red-400 mx-auto mb-2"/>
          <p className="font-bold text-red-700">Failed to load</p>
          <p className="text-red-500 text-xs mt-1 mb-3">{error}</p>
          <button onClick={loadData} className="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Retry</button>
        </div>
      ) : (
        <>
          {/* OVERVIEW */}
          {tab==="overview" && (
            <div className="space-y-4">
              <div className="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-50">
                <p className="p-4 text-sm font-bold text-gray-700 flex items-center gap-2"><Activity className="w-4 h-4 text-amber-500"/> Recent Login Activity</p>
                {(data?.recentAudit||[]).length === 0 && <p className="p-6 text-center text-gray-400 text-sm">No recent activity.</p>}
                {(data?.recentAudit||[]).map((a:any)=>(
                  <div key={a.id} className="p-3 flex items-center justify-between">
                    <div>
                      <p className="text-sm font-bold text-gray-900">{a.user_name}</p>
                      <p className="text-[10px] text-gray-400">{new Date(a.created_at).toLocaleString()}</p>
                    </div>
                    <span className={`text-[9px] font-bold px-2 py-1 rounded-full uppercase
                      ${a.action==="login"?"bg-blue-100 text-blue-600":a.action==="failed_login"?"bg-red-100 text-red-600":"bg-gray-100 text-gray-500"}`}>
                      {a.action?.replace("_"," ")}
                    </span>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* PAGE ACCESS (The main feature requested in the screenshot) */}
          {tab==="page_access" && (
            <div className="space-y-4">
              <div className="bg-cyan-50 border border-cyan-100 rounded-xl p-3 flex items-start gap-2">
                <ShieldAlert className="w-4 h-4 text-cyan-600 mt-0.5 flex-shrink-0"/>
                <p className="text-sm text-cyan-800">Click a user below, then toggle which pages they can access. If nothing is selected, the user will have NO access to restricted pages.</p>
              </div>

              <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Left Column: Users */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col h-[600px]">
                  <div className="p-4 border-b border-gray-100 bg-gray-50">
                    <p className="text-xs font-bold text-gray-500 tracking-widest uppercase">Select User</p>
                  </div>
                  <div className="flex-1 overflow-y-auto p-2 space-y-1">
                    {(data?.allUsers||[]).map((u:any)=>(
                      <button
                        key={u.id}
                        onClick={()=>{
                          setAccessUser(u);
                          setSelectedPages(u.allowed_pages || []);
                        }}
                        className={`w-full flex items-center gap-3 p-3 rounded-xl text-left transition-colors
                          ${accessUser?.id === u.id ? "bg-amber-50 border border-amber-200" : "hover:bg-gray-50 border border-transparent"}`}
                      >
                        <div className={`w-10 h-10 rounded-full flex items-center justify-center font-black text-sm flex-shrink-0
                          ${accessUser?.id === u.id ? "bg-amber-500 text-white" : "bg-gray-100 text-gray-500"}`}>
                          {u.full_name?.charAt(0)||"U"}
                        </div>
                        <div className="flex-1 min-w-0">
                          <p className={`font-bold text-sm truncate ${accessUser?.id === u.id ? "text-amber-900" : "text-gray-900"}`}>{u.full_name}</p>
                          <p className="text-xs text-gray-500 capitalize">{u.role}</p>
                        </div>
                        <ChevronRight className={`w-4 h-4 ${accessUser?.id === u.id ? "text-amber-500" : "text-transparent"}`}/>
                      </button>
                    ))}
                  </div>
                </div>

                {/* Right Column: Permissions */}
                <div className="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col h-[600px]">
                  {accessUser ? (
                    <>
                      <div className="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                        <p className="text-xs font-bold text-gray-500 tracking-widest uppercase">
                          Page Permissions — <span className="text-amber-600">{accessUser.full_name}</span>
                        </p>
                        <div className="flex items-center gap-2">
                          <button onClick={selectAllPages} className="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50">Select All</button>
                          <button onClick={()=>setSelectedPages([])} className="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50">Clear All</button>
                          <button onClick={savePageAccess} disabled={savingAccess} className="px-4 py-1.5 bg-amber-500 text-white rounded-lg text-xs font-bold hover:bg-amber-600 shadow-sm flex items-center gap-2">
                            {savingAccess ? <Loader2 className="w-3 h-3 animate-spin"/> : <Lock className="w-3 h-3"/>}
                            Save Access
                          </button>
                        </div>
                      </div>
                      <div className="flex-1 overflow-y-auto p-6 space-y-8">
                        {pageGroups.map((group) => (
                          <div key={group.title}>
                            <h3 className="text-xs font-black text-gray-900 mb-3">{group.title}</h3>
                            <div className="flex flex-wrap gap-2">
                              {group.pages.map((page) => {
                                const isSelected = selectedPages.includes(page.id);
                                return (
                                  <button
                                    key={page.id}
                                    onClick={() => togglePage(page.id)}
                                    className={`px-3 py-2 border rounded-lg text-xs font-bold flex items-center gap-2 transition-all
                                      ${isSelected ? "bg-[#fef3c7] border-amber-300 text-amber-900" : "bg-white border-gray-200 text-gray-500 hover:border-gray-300"}`}
                                  >
                                    <div className={`w-3 h-3 rounded-full border ${isSelected ? "bg-amber-500 border-amber-600" : "bg-white border-gray-300"}`} />
                                    {page.label}
                                  </button>
                                );
                              })}
                            </div>
                          </div>
                        ))}
                      </div>
                    </>
                  ) : (
                    <div className="flex-1 flex flex-col items-center justify-center p-8 text-gray-400">
                      <Users className="w-12 h-12 mb-3 text-gray-200"/>
                      <p className="text-sm font-bold">No User Selected</p>
                      <p className="text-xs mt-1 text-center">Select a user from the left list to configure their page permissions.</p>
                    </div>
                  )}
                </div>
              </div>
            </div>
          )}

          {/* CREATE STAFF */}
          {tab==="create_staff" && (
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div className="p-4 border-b bg-gray-50 flex items-center gap-2">
                <UserPlus className="w-4 h-4 text-amber-500"/>
                <span className="font-bold text-sm">Add New Staff Member</span>
              </div>
              <div className="p-4">
                {tempPass ? (
                  <div className="text-center py-4">
                    <CheckCircle className="w-12 h-12 text-green-500 mx-auto mb-3"/>
                    <h3 className="font-black text-lg mb-1">Account Created!</h3>
                    <p className="text-gray-500 text-sm mb-3">Share this one-time password:</p>
                    <div className="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-4 text-2xl font-mono font-black tracking-widest text-gray-900 mb-4 select-all">{tempPass}</div>
                    <p className="text-xs text-gray-400 mb-4">Staff must change this on first login.</p>
                    <button onClick={()=>{ setTempPass(""); setSf({first_name:"",last_name:"",email:"",phone_number:"",address:"",role:""}); }}
                      className="bg-amber-500 text-white font-bold px-6 py-2 rounded-xl text-sm">Create Another</button>
                  </div>
                ) : (
                  <form onSubmit={createStaff} className="space-y-3">
                    <div className="grid grid-cols-2 gap-3">
                      <div>
                        <label className="text-xs font-bold text-gray-500 mb-1 block">First Name *</label>
                        <input required type="text" value={sf.first_name} onChange={e=>setSf({...sf,first_name:e.target.value})}
                          className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm"/>
                      </div>
                      <div>
                        <label className="text-xs font-bold text-gray-500 mb-1 block">Last Name *</label>
                        <input required type="text" value={sf.last_name} onChange={e=>setSf({...sf,last_name:e.target.value})}
                          className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm"/>
                      </div>
                    </div>
                    <div>
                      <label className="text-xs font-bold text-gray-500 mb-1 block">Email *</label>
                      <input required type="email" value={sf.email} onChange={e=>setSf({...sf,email:e.target.value})}
                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm"/>
                    </div>
                    <div>
                      <label className="text-xs font-bold text-gray-500 mb-1 block">Phone</label>
                      <input type="text" value={sf.phone_number} onChange={e=>setSf({...sf,phone_number:e.target.value})}
                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm"/>
                    </div>
                    <div>
                      <label className="text-xs font-bold text-gray-500 mb-1 block">Role *</label>
                      <select required value={sf.role} onChange={e=>setSf({...sf,role:e.target.value})}
                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm">
                        <option value="">Select role...</option>
                        {(data?.roles||[]).map((r:any)=><option key={r.id} value={r.name}>{r.label}</option>)}
                      </select>
                    </div>
                    <button type="submit" disabled={submitting}
                      className="w-full bg-amber-500 text-white font-bold py-3 rounded-xl disabled:opacity-60 mt-2 hover:bg-amber-600 transition-colors">
                      {submitting?"Creating Account...":"Create Staff Account"}
                    </button>
                  </form>
                )}
              </div>
            </div>
          )}

          {/* ALL USERS */}
          {tab==="all_users" && (
            <div className="space-y-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"/>
                <input type="text" placeholder="Search users by name, email or role..." value={search} onChange={e=>setSearch(e.target.value)}
                  className="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm"/>
              </div>
              <p className="text-xs text-gray-400">{filteredUsers.length} user{filteredUsers.length!==1?"s":""} found</p>
              {filteredUsers.map((u:any)=>(
                <div key={u.id} className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col md:flex-row md:items-center">
                  <div className="p-4 flex items-center gap-3 flex-1">
                    <div className={`w-11 h-11 rounded-full flex items-center justify-center font-black text-white text-sm flex-shrink-0
                      ${u.approval_status==="approved"?"bg-blue-500":u.approval_status==="pending"?"bg-amber-500":"bg-red-400"}`}>
                      {u.full_name?.charAt(0)||"?"}
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="font-bold text-sm text-gray-900">{u.full_name}</p>
                      <p className="text-xs text-gray-400 truncate">{u.email}</p>
                      <div className="flex items-center gap-2 mt-1">
                        <span className="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{u.role}</span>
                        <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${statusColor(u.approval_status)}`}>{u.approval_status}</span>
                        {u.is_disabled && <span className="text-[10px] font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Disabled</span>}
                        {u.deleted_at && <span className="text-[10px] font-bold bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full">Archived</span>}
                      </div>
                    </div>
                  </div>
                  <div className="bg-gray-50 md:bg-transparent border-t md:border-t-0 border-gray-100 px-4 py-3 md:py-4 flex flex-wrap gap-2 items-center justify-end">
                    {/* Placeholder for action buttons to avoid complex imports not strictly needed for UI match */}
                    <button className="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors">Manage</button>
                  </div>
                </div>
              ))}
              {filteredUsers.length===0 && <p className="text-center text-gray-400 py-8 text-sm">No users found.</p>}
            </div>
          )}

          {/* LOGIN HISTORY */}
          {tab==="login_history" && (
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div className="p-4 border-b bg-gray-50 flex items-center justify-between">
                <div className="flex items-center gap-2"><Activity className="w-4 h-4 text-blue-500"/><span className="font-bold text-sm">System Audit Trail</span></div>
                <button onClick={loadAudit} className="p-1.5 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-sm"><RefreshCw className="w-3 h-3 text-gray-500"/></button>
              </div>
              <div className="divide-y divide-gray-50 max-h-[65vh] overflow-y-auto">
                {auditLogs.length===0 && <p className="p-8 text-center text-gray-400 text-sm">No audit logs found.</p>}
                {auditLogs.map((log:any)=>(
                  <div key={log.id} className="p-3 hover:bg-gray-50 transition-colors">
                    <div className="flex items-start justify-between gap-2">
                      <div className="flex-1 min-w-0">
                        <p className="text-xs font-bold text-gray-900">{log.user_name} <span className="text-gray-400 font-normal">({log.user_role})</span></p>
                        <p className="text-[10px] text-gray-500 mt-0.5 line-clamp-1">{log.notes||"—"}</p>
                        <p className="text-[9px] text-gray-400 mt-0.5">{new Date(log.created_at).toLocaleString()} · {log.ip_address||"—"}</p>
                      </div>
                      <span className={`text-[9px] font-bold px-2 py-1 rounded-full uppercase flex-shrink-0
                        ${log.action==="login"?"bg-blue-100 text-blue-600":log.action==="failed_login"?"bg-red-100 text-red-600":log.action==="created"?"bg-green-100 text-green-600":"bg-gray-100 text-gray-500"}`}>
                        {log.action?.replace(/_/g," ")}
                      </span>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* SECURITY */}
          {tab==="system_security" && (
            <div className="space-y-4">
              <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="p-4 bg-red-50 border-b border-red-100 flex items-center gap-2">
                  <ShieldAlert className="w-5 h-5 text-red-600"/>
                  <div>
                    <span className="font-bold text-sm text-red-700">Archive Deletion Lock</span>
                    <p className="text-xs text-red-500">Set a master password required to permanently delete archived records.</p>
                  </div>
                </div>
                <div className="p-6">
                  <form onSubmit={e=>e.preventDefault()} className="max-w-md space-y-4">
                    <div>
                      <label className="text-xs font-bold text-gray-700 mb-1 block">New Password (min 6 chars)</label>
                      <div className="relative">
                        <input required minLength={6} type={showPass?"text":"password"} value={archForm.archive_password}
                          onChange={e=>setArchForm({...archForm,archive_password:e.target.value})}
                          className="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm pr-10 focus:ring-2 focus:ring-amber-500 outline-none"/>
                        <button type="button" onClick={()=>setShowPass(!showPass)} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                          {showPass?<EyeOff className="w-4 h-4"/>:<Eye className="w-4 h-4"/>}
                        </button>
                      </div>
                    </div>
                    <div>
                      <label className="text-xs font-bold text-gray-700 mb-1 block">Confirm Password</label>
                      <input required minLength={6} type={showPass?"text":"password"} value={archForm.archive_password_confirmation}
                        onChange={e=>setArchForm({...archForm,archive_password_confirmation:e.target.value})}
                        className="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none"/>
                    </div>
                    <button type="submit" className="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition-colors">
                      Update Deletion Password
                    </button>
                  </form>
                </div>
              </div>
            </div>
          )}
        </>
      )}
    </div>
  );
}
