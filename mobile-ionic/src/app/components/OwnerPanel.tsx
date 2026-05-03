import { useState, useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";
import { toast } from "sonner";
import {
  Crown, Shield, Users, UserPlus, Activity, Lock, ShieldAlert,
  CheckCircle, XCircle, RefreshCw, Loader2, X, ChevronRight,
  LayoutDashboard, Eye, EyeOff, Search
} from "lucide-react";

export function OwnerPanel() {
  const { user } = useAuth();
  const [tab, setTab] = useState("overview");
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

  // Page access modal
  const [accessUser, setAccessUser] = useState<any>(null);

  useEffect(() => { loadData(); }, []);
  useEffect(() => { if (tab === "audit") loadAudit(); }, [tab]);

  const loadData = async () => {
    setLoading(true); setError(null);
    try {
      const r = await api.get("/super-admin/overview");
      if (r.data.success) setData(r.data);
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

  const approveUser = async (id: number) => {
    try {
      const r = await api.post(`/super-admin/users/${id}/approve`);
      toast.success(r.data.message); loadData();
    } catch(e:any) { toast.error(e?.response?.data?.message || "Failed."); }
  };

  const rejectUser = async (id: number) => {
    try {
      const r = await api.post(`/super-admin/users/${id}/reject`);
      toast.success(r.data.message); loadData();
    } catch(e:any) { toast.error(e?.response?.data?.message || "Failed."); }
  };

  const toggleDisable = async (id: number, currentlyDisabled: boolean) => {
    try {
      const r = await api.post(`/super-admin/users/${id}/toggle-disable`, {
        is_disabled: !currentlyDisabled,
        reason: !currentlyDisabled ? "Disabled via Owner Panel (Mobile)" : ""
      });
      toast.success(r.data.message); loadData();
    } catch(e:any) { toast.error(e?.response?.data?.message || "Failed."); }
  };

  const archiveUser = async (id: number) => {
    if (!confirm("Archive this user?")) return;
    try {
      const r = await api.post(`/super-admin/users/${id}/archive`);
      toast.success(r.data.message); loadData();
    } catch(e:any) { toast.error(e?.response?.data?.message || "Failed."); }
  };

  const saveArchivePassword = async (e: React.FormEvent) => {
    e.preventDefault();
    if (archForm.archive_password !== archForm.archive_password_confirmation) { toast.error("Passwords do not match."); return; }
    try {
      const r = await api.post("/super-admin/archive-password", archForm);
      toast.success(r.data.message);
      setArchForm({archive_password:"",archive_password_confirmation:""});
    } catch(e:any) { toast.error(e?.response?.data?.message || "Failed."); }
  };

  if (user?.role !== "super_admin") return (
    <div className="flex flex-col items-center justify-center min-h-[400px] gap-4 p-6">
      <Shield className="w-16 h-16 text-red-400"/>
      <h2 className="text-xl font-black text-gray-900">Access Denied</h2>
      <p className="text-gray-500 text-sm text-center">You need Owner (Super Admin) privileges to access this panel.</p>
    </div>
  );

  const tabs = [
    {id:"overview", label:"Overview", icon:LayoutDashboard},
    {id:"staff",    label:"New Staff",  icon:UserPlus},
    {id:"users",    label:"Users",      icon:Users},
    {id:"audit",    label:"Audit",      icon:Activity},
    {id:"security", label:"Security",   icon:Lock},
  ];

  const filteredUsers = (data?.allUsers||[]).filter((u:any) =>
    u.full_name?.toLowerCase().includes(search.toLowerCase()) ||
    u.email?.toLowerCase().includes(search.toLowerCase()) ||
    u.role?.toLowerCase().includes(search.toLowerCase())
  );

  const statusColor = (s:string) => s==="approved"?"bg-green-100 text-green-700":s==="pending"?"bg-amber-100 text-amber-700":"bg-red-100 text-red-700";

  return (
    <div className="space-y-4 pb-10">
      {/* Header */}
      <div className="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 text-white">
        <div className="flex items-center gap-4">
          <div className="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <Crown className="w-6 h-6 text-amber-100"/>
          </div>
          <div className="flex-1">
            <h1 className="text-xl font-black tracking-tight">Owner Control Center</h1>
            <p className="text-amber-100 text-sm">{user?.full_name}</p>
          </div>
          <button onClick={loadData} className="p-2 bg-white/20 rounded-xl">
            <RefreshCw className="w-4 h-4 text-white"/>
          </button>
        </div>

        {/* Quick stats */}
        {data && (
          <div className="grid grid-cols-3 gap-2 mt-4">
            {[["Total",data.stats?.total_users,"👥"],["Active",data.stats?.active_users,"✅"],["Rejected",data.stats?.rejected_users,"❌"]].map(([l,v,e]:any)=>(
              <div key={l} className="bg-white/15 rounded-xl p-2 text-center">
                <p className="text-lg font-black">{e} {v??0}</p>
                <p className="text-[10px] text-amber-100 uppercase font-bold">{l}</p>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Tabs */}
      <div className="flex overflow-x-auto gap-2 pb-1 scrollbar-hide">
        {tabs.map(t=>(
          <button key={t.id} onClick={()=>setTab(t.id)}
            className={`flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold whitespace-nowrap transition-all
              ${tab===t.id?"bg-amber-500 text-white shadow-md":"bg-white text-gray-500 border border-gray-200"}`}>
            <t.icon className="w-4 h-4"/>{t.label}
          </button>
        ))}
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

          {/* CREATE STAFF */}
          {tab==="staff" && (
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
                      className="w-full bg-amber-500 text-white font-bold py-3 rounded-xl disabled:opacity-60 mt-2">
                      {submitting?"Creating Account...":"Create Staff Account"}
                    </button>
                  </form>
                )}
              </div>
            </div>
          )}

          {/* ALL USERS */}
          {tab==="users" && (
            <div className="space-y-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"/>
                <input type="text" placeholder="Search users..." value={search} onChange={e=>setSearch(e.target.value)}
                  className="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm"/>
              </div>
              <p className="text-xs text-gray-400">{filteredUsers.length} user{filteredUsers.length!==1?"s":""} found</p>
              {filteredUsers.map((u:any)=>(
                <div key={u.id} className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                  <div className="p-4 flex items-center gap-3">
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
                  <div className="bg-gray-50 border-t border-gray-100 px-4 py-2 flex flex-wrap gap-2">
                    {u.approval_status==="pending" && <>
                      <button onClick={()=>approveUser(u.id)} className="px-3 py-1.5 bg-green-500 text-white rounded-lg text-xs font-bold">✓ Approve</button>
                      <button onClick={()=>rejectUser(u.id)} className="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-bold">✗ Reject</button>
                    </>}
                    {u.approval_status==="approved" && !u.deleted_at && (
                      <button onClick={()=>toggleDisable(u.id,!!u.is_disabled)}
                        className={`px-3 py-1.5 rounded-lg text-xs font-bold ${u.is_disabled?"bg-green-100 text-green-700":"bg-orange-100 text-orange-700"}`}>
                        {u.is_disabled?"Enable Access":"Disable Access"}
                      </button>
                    )}
                    {!u.deleted_at && (
                      <button onClick={()=>archiveUser(u.id)} className="px-3 py-1.5 bg-gray-200 text-gray-600 rounded-lg text-xs font-bold">Archive</button>
                    )}
                  </div>
                </div>
              ))}
              {filteredUsers.length===0 && <p className="text-center text-gray-400 py-8 text-sm">No users found.</p>}
            </div>
          )}

          {/* AUDIT LOGS */}
          {tab==="audit" && (
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div className="p-4 border-b bg-gray-50 flex items-center justify-between">
                <div className="flex items-center gap-2"><Activity className="w-4 h-4 text-blue-500"/><span className="font-bold text-sm">System Audit Trail</span></div>
                <button onClick={loadAudit} className="p-1.5 bg-gray-200 rounded-lg"><RefreshCw className="w-3 h-3 text-gray-500"/></button>
              </div>
              <div className="divide-y divide-gray-50 max-h-[65vh] overflow-y-auto">
                {auditLogs.length===0 && <p className="p-8 text-center text-gray-400 text-sm">No audit logs found.</p>}
                {auditLogs.map((log:any)=>(
                  <div key={log.id} className="p-3">
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
          {tab==="security" && (
            <div className="space-y-4">
              <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="p-4 bg-red-50 border-b border-red-100">
                  <div className="flex items-center gap-2 mb-1"><ShieldAlert className="w-4 h-4 text-red-600"/><span className="font-bold text-sm text-red-700">Archive Deletion Lock</span></div>
                  <p className="text-xs text-red-500">Set a master password required to permanently delete archived records.</p>
                </div>
                <div className="p-4">
                  <form onSubmit={saveArchivePassword} className="space-y-3">
                    <div>
                      <label className="text-xs font-bold text-gray-500 mb-1 block">New Password (min 6 chars)</label>
                      <div className="relative">
                        <input required minLength={6} type={showPass?"text":"password"} value={archForm.archive_password}
                          onChange={e=>setArchForm({...archForm,archive_password:e.target.value})}
                          className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm pr-10"/>
                        <button type="button" onClick={()=>setShowPass(!showPass)} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                          {showPass?<EyeOff className="w-4 h-4"/>:<Eye className="w-4 h-4"/>}
                        </button>
                      </div>
                    </div>
                    <div>
                      <label className="text-xs font-bold text-gray-500 mb-1 block">Confirm Password</label>
                      <input required minLength={6} type={showPass?"text":"password"} value={archForm.archive_password_confirmation}
                        onChange={e=>setArchForm({...archForm,archive_password_confirmation:e.target.value})}
                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm"/>
                    </div>
                    <button type="submit" className="w-full bg-gray-900 text-white font-bold py-3 rounded-xl">
                      Update Deletion Password
                    </button>
                  </form>
                </div>
              </div>

              <div className="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <p className="text-xs font-bold text-amber-700 mb-2">⚠️ Security Notes</p>
                <ul className="text-xs text-amber-600 space-y-1">
                  <li>• Archive password is required to permanently delete records</li>
                  <li>• All admin actions are logged in the Audit Trail</li>
                  <li>• Disabled accounts cannot log in to the system</li>
                  <li>• Archived accounts are soft-deleted and can be restored</li>
                </ul>
              </div>
            </div>
          )}
        </>
      )}
    </div>
  );
}
